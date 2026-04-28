<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('locations:close-expired', function () {
    $closedCount = app(\App\Support\LocationSessionService::class)
        ->closeExpiredOpenLocations();

    $this->info("Closed {$closedCount} expired location session(s).");
})->purpose('Close expired open locations based on DATE_OPEN and JWT TTL');

Schedule::command('locations:close-expired')->everyMinute();
