<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/**
 * Shared installation engine used by the web installer (/install)
 * and the CLI installer (php artisan nexus:install).
 */
class InstallRunner
{
    /**
     * @param array{
     *   app_name:string, app_url:string, db_connection:string,
     *   db_host?:string, db_port?:string|int, db_database?:string,
     *   db_username?:string, db_password?:string,
     *   purchase_code?:string, admin_name:string, admin_email:string,
     *   admin_password:string, demo_data:bool
     * } $data
     *
     * @throws \Throwable on any failure (caller renders/reports it)
     */
    public static function run(array $data, bool $deferEnvWrite = false): void
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');

        static::log('Installation started');

        // 1) Verify database connectivity BEFORE touching .env
        $dbConfig = $data['db_connection'] === 'sqlite'
            ? ['driver' => 'sqlite', 'database' => database_path('database.sqlite'), 'prefix' => '', 'foreign_key_constraints' => true]
            : ['driver' => 'mysql', 'host' => $data['db_host'], 'port' => $data['db_port'], 'database' => $data['db_database'], 'username' => $data['db_username'], 'password' => $data['db_password'] ?? '', 'charset' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci', 'prefix' => '', 'strict' => true];

        if ($data['db_connection'] === 'sqlite' && ! file_exists($dbConfig['database'])) {
            touch($dbConfig['database']);
        }

        Config::set('database.connections.installer_test', $dbConfig);
        DB::connection('installer_test')->getPdo();
        static::log('Database connection verified ('.$data['db_connection'].')');

        // Environment values — written LAST (see step 7). Writing .env early
        // breaks `php artisan serve`, which restarts itself when .env changes
        // and would kill this very request mid-installation.
        $envValues = [
            'APP_NAME'       => $data['app_name'],
            'APP_ENV'        => 'production',
            'APP_DEBUG'      => 'false',
            'APP_URL'        => rtrim($data['app_url'], '/'),
            'DB_CONNECTION'  => $data['db_connection'],
            'DB_HOST'        => $data['db_host'] ?? '127.0.0.1',
            'DB_PORT'        => $data['db_port'] ?? '3306',
            'DB_DATABASE'    => $data['db_connection'] === 'sqlite' ? database_path('database.sqlite') : $data['db_database'],
            'DB_USERNAME'    => $data['db_username'] ?? '',
            'DB_PASSWORD'    => $data['db_password'] ?? '',
            'SESSION_DRIVER' => 'database',
        ];

        // 3) Route everything through the verified connection
        Config::set('database.default', 'installer_test');
        DB::purge();

        // 4) Migrate + core seeds
        Artisan::call('migrate', ['--force' => true, '--database' => 'installer_test']);
        static::log('Migrations complete: '.trim(Artisan::output()));

        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'FaqSeeder', '--force' => true]);
        static::log('Core seeders complete');

        // 5) Super-admin
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $admin = User::on('installer_test')->firstOrCreate(
            ['email' => $data['admin_email']],
            ['name' => $data['admin_name'], 'password' => Hash::make($data['admin_password']), 'email_verified_at' => now()]
        );
        $admin->syncRoles('super-admin');
        static::log('Super-admin created: '.$data['admin_email']);

        // 6) Optional demo content
        if ($data['demo_data']) {
            Artisan::call('db:seed', ['--class' => 'DemoUsersSeeder', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'WorkspaceSeeder', '--force' => true]);
            static::log('Demo content installed');
        }

        // 7) Lock installer, then write .env
        Installer::markInstalled($data['purchase_code'] ?? '');

        if ($deferEnvWrite) {
            // Web installer: write .env AFTER the response has been sent, so
            // `php artisan serve` restarting on the .env change can no longer
            // kill the in-flight request. serve boots the fresh environment
            // automatically for the next request.
            app()->terminating(function () use ($envValues) {
                Installer::writeEnv($envValues);
                Artisan::call('config:clear');
                static::log('.env written (deferred, post-response)');
            });
            static::log('Installation finished — .env write deferred to after response');
        } else {
            Installer::writeEnv($envValues);
            Artisan::call('config:clear');
            static::log('.env written');
            static::log('Installation finished successfully');
        }
    }

    public static function log(string $message): void
    {
        @file_put_contents(
            storage_path('logs/installer.log'),
            '['.date('Y-m-d H:i:s')."] {$message}\n",
            FILE_APPEND
        );
    }
}
