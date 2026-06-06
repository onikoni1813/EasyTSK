<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('leaderboard:update')->everyThirtyMinutes();
Schedule::command('task:rotate')->dailyAt('00:00');
Schedule::command('screenshots:cleanup')->dailyAt('01:00');
