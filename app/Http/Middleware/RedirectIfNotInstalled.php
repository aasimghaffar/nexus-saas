<?php

namespace App\Http\Middleware;

use App\Support\Installer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    /**
     * Runs BEFORE the rest of the web stack (prepended in bootstrap/app.php)
     * so it can self-heal a missing .env / APP_KEY before Laravel's cookie
     * encrypter needs them.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->healEnvironment();

        if (! Installer::isInstalled()
            && ! $request->is('install*')
            && ! $request->is('livewire/*')
            && ! $request->is('build/*')) {
            return redirect('/install');
        }

        return $next($request);
    }

    /**
     * Direct uploads often arrive without .env or with an empty APP_KEY.
     * Create/complete them silently so the first request never 500s.
     */
    private function healEnvironment(): void
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath) && file_exists(base_path('.env.example'))) {
            copy(base_path('.env.example'), $envPath);
        }

        if (! config('app.key') && file_exists($envPath) && is_writable($envPath)) {
            $env = file_get_contents($envPath);
            $key = 'base64:'.base64_encode(random_bytes(32));

            if (preg_match('/^APP_KEY=\s*$/m', $env)) {
                $env = preg_replace('/^APP_KEY=\s*$/m', 'APP_KEY='.$key, $env);
            } elseif (! preg_match('/^APP_KEY=/m', $env)) {
                $env .= "\nAPP_KEY={$key}";
            } else {
                // APP_KEY line exists with a value the config didn't pick up (cached config)
                preg_match('/^APP_KEY=(.+)$/m', $env, $m);
                $key = trim($m[1]);
            }

            file_put_contents($envPath, $env);
            Config::set('app.key', $key);
        }
    }
}
