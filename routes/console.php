<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily encrypted database backups
Schedule::command('backup:database --encrypt')
    ->dailyAt(config('backup.schedule_time', '02:00'))
    ->timezone(config('app.timezone', 'Africa/Dar_es_Salaam'))
    ->onFailure(function () {
        // Send notification on failure
        if (config('backup.notification_email')) {
            \Illuminate\Support\Facades\Mail::raw(
                'Database backup failed at '.now()->format('Y-m-d H:i:s'),
                function ($message) {
                    $message->to(config('backup.notification_email'))
                        ->subject('Database Backup Failed - '.config('app.name'));
                }
            );
        }
    });

// Schedule daily birthday SMS sending (runs at 8:00 AM)
Schedule::command('sms:send-birthday')
    ->dailyAt('08:00')
    ->timezone(config('app.timezone', 'Africa/Dar_es_Salaam'))
    ->withoutOverlapping()
    ->onFailure(function () {
        Log::error('Birthday SMS scheduled task failed at '.now()->format('Y-m-d H:i:s'));
    });
