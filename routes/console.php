<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Register the database ping command to run every 5 minutes
\Illuminate\Support\Facades\Schedule::command('db:ping')->everyFiveMinutes();
