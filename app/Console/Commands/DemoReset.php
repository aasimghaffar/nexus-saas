<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DemoReset extends Command
{
    protected $signature = 'nexus:demo-reset';

    protected $description = 'Reset the database to pristine demo state (for public demo servers). Schedule hourly.';

    public function handle(): int
    {
        if (! config('nexus.demo_mode')) {
            $this->error('Refusing to reset: nexus.demo_mode is disabled. Set NEXUS_DEMO=true only on demo servers.');

            return self::FAILURE;
        }

        $this->warn('Resetting demo database…');
        Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true], $this->output);
        $this->info('Demo data restored.');

        return self::SUCCESS;
    }
}
