<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule task notification system
Schedule::command('tasks:process-notifications')
    ->everyFiveMinutes()
    ->name('process-task-notifications')
    ->withoutOverlapping();
