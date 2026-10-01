<?php

namespace App\Console\Commands;

use App\Support\Installer;
use App\Support\InstallRunner;
use Illuminate\Console\Command;

class NexusInstall extends Command
{
    protected $signature = 'nexus:install
        {--app-name= : Application name}
        {--app-url= : Application URL, e.g. http://localhost:8000}
        {--db=sqlite : Database driver (sqlite or mysql)}
        {--db-host=127.0.0.1} {--db-port=3306} {--db-database=} {--db-username=} {--db-password=}
        {--admin-name=} {--admin-email=} {--admin-password=}
        {--demo : Install demo content}
        {--fresh : Remove the installed lock and re-install}';

    protected $description = 'Install Nexus SaaS from the command line (alternative to the /install web page).';

    public function handle(): int
    {
        if ($this->option('fresh') && file_exists(Installer::markerPath())) {
            unlink(Installer::markerPath());
        }

        if (Installer::isInstalled()) {
            $this->error('Already installed. Delete storage/installed.json (or pass --fresh) to re-install.');

            return self::FAILURE;
        }

        // Requirements
        $failed = array_filter(Installer::requirements(), fn ($r) => ! $r[1]);
        foreach ($failed as [$label, , $detail]) {
            $this->error("Requirement failed: {$label} ({$detail})");
        }
        if ($failed) {
            return self::FAILURE;
        }

        $db = $this->option('db') ?: 'sqlite';

        $data = [
            'app_name'       => $this->option('app-name') ?: $this->ask('Application name', 'Nexus SaaS'),
            'app_url'        => $this->option('app-url') ?: $this->ask('Application URL', 'http://localhost:8000'),
            'db_connection'  => in_array($db, ['mysql', 'sqlite'], true) ? $db : 'sqlite',
            'db_host'        => $this->option('db-host'),
            'db_port'        => $this->option('db-port'),
            'db_database'    => $this->option('db-database') ?: ($db === 'mysql' ? $this->ask('MySQL database name') : null),
            'db_username'    => $this->option('db-username') ?: ($db === 'mysql' ? $this->ask('MySQL username', 'root') : null),
            'db_password'    => $this->option('db-password') ?? ($db === 'mysql' ? (string) $this->secret('MySQL password (blank for none)') : null),
            'admin_name'     => $this->option('admin-name') ?: $this->ask('Admin full name'),
            'admin_email'    => $this->option('admin-email') ?: $this->ask('Admin email'),
            'admin_password' => $this->option('admin-password') ?: $this->secret('Admin password (min 8 chars)'),
            'demo_data'      => (bool) $this->option('demo'),
        ];

        if (strlen((string) $data['admin_password']) < 8) {
            $this->error('Admin password must be at least 8 characters.');

            return self::FAILURE;
        }

        $this->info('Installing…');

        try {
            InstallRunner::run($data);
        } catch (\Throwable $e) {
            InstallRunner::log('FAILED (cli): '.$e->getMessage());
            $this->error('Installation failed: '.$e->getMessage());
            $this->line('See storage/logs/installer.log for the step log.');

            return self::FAILURE;
        }

        $this->info('✔ Installed. Sign in at '.$data['app_url'].'/login as '.$data['admin_email']);
        if ($data['demo_data']) {
            $this->line('Demo accounts (password "password"): sarah@example.com (admin), liam@example.com (viewer)');
        }

        return self::SUCCESS;
    }
}
