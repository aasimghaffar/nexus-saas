<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Public demo servers: restore pristine demo data hourly (no-op unless NEXUS_DEMO=true).
Schedule::command('nexus:demo-reset')->hourly()->when(fn () => config('nexus.demo_mode') === true);
