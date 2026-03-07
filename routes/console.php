<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Automated database backups
Schedule::command('backup:run --only-db')
    ->dailyAt('02:00')
    ->onFailure(fn () => logger()->critical('Daily DB backup failed'))
    ->onSuccess(fn () => logger()->info('Daily DB backup completed'));

// Full backup (DB + files) weekly on Sunday
Schedule::command('backup:run')
    ->weeklyOn(0, '03:00')
    ->onFailure(fn () => logger()->critical('Weekly full backup failed'));

// Clean up old backups daily
Schedule::command('backup:clean')
    ->dailyAt('02:30');

// Monitor backup health daily
Schedule::command('backup:monitor')
    ->dailyAt('09:00');
