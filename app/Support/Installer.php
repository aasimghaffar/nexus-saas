<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class Installer
{
    public static function markerPath(): string
    {
        return storage_path('installed.json');
    }

    public static function isInstalled(): bool
    {
        return File::exists(static::markerPath());
    }

    public static function markInstalled(string $purchaseCode = ''): void
    {
        File::put(static::markerPath(), json_encode(array_filter([
            'version'       => config('nexus.version', '1.0.0'),
            'installed_at'  => now()->toIso8601String(),
            'purchase_hash' => $purchaseCode !== '' ? hash('sha256', $purchaseCode) : null,
        ]), JSON_PRETTY_PRINT));
    }

    /**
     * Requirements the target server must meet.
     */
    public static function requirements(): array
    {
        $extensions = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'ctype', 'json', 'curl', 'fileinfo', 'gd'];

        $checks = [
            ['PHP >= 8.2', version_compare(PHP_VERSION, '8.2.0', '>='), 'Running '.PHP_VERSION],
        ];

        foreach ($extensions as $ext) {
            $checks[] = ["PHP extension: {$ext}", extension_loaded($ext), extension_loaded($ext) ? 'Loaded' : 'Missing'];
        }

        foreach (['storage', 'bootstrap/cache', '.env or base directory'] as $i => $label) {
            $path = [storage_path(), base_path('bootstrap/cache'), File::exists(base_path('.env')) ? base_path('.env') : base_path()][$i];
            $checks[] = ["Writable: {$label}", is_writable($path), is_writable($path) ? 'OK' : 'Not writable'];
        }

        return $checks;
    }

    /**
     * Write key=value pairs into .env (creating from .env.example when missing).
     */
    public static function writeEnv(array $values): void
    {
        $envPath = base_path('.env');

        if (! File::exists($envPath)) {
            File::copy(base_path('.env.example'), $envPath);
        }

        $env = File::get($envPath);

        // Guarantee an application key exists (composer create-project normally
        // does this; direct-upload installs may arrive without one).
        if (preg_match('/^APP_KEY=\s*$/m', $env) && ! isset($values['APP_KEY'])) {
            $values['APP_KEY'] = 'base64:'.base64_encode(random_bytes(32));
        }

        foreach ($values as $key => $value) {
            $value = (string) $value;
            if (preg_match('/\s|#|"/', $value)) {
                $value = '"'.addslashes($value).'"';
            }
            $env = preg_match("/^{$key}=.*/m", $env)
                ? preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env)
                : $env."\n{$key}={$value}";
        }

        File::put($envPath, $env);
    }
}
