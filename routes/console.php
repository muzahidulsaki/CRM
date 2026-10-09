<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// SRS Section 20, 22, 25, 32: Automated daily reminder routine
Schedule::command('crm:send-reminders')->dailyAt('09:00');

// SRS Section 53: Automated daily database backup routine at midnight
Schedule::command('crm:backup-db')->dailyAt('00:00');

