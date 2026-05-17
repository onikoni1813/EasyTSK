<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

\Illuminate\Support\Facades\Schedule::command('leaderboard:update')->everyThirtyMinutes();
\Illuminate\Support\Facades\Schedule::command('task:rotate')->dailyAt('00:00');
\Illuminate\Support\Facades\Schedule::command('screenshots:cleanup')->dailyAt('01:00');
