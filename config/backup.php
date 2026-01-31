<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for automated database backups including encryption
    | and off-site storage settings.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Encryption Settings
    |--------------------------------------------------------------------------
    |
    | Enable encryption for backups. When enabled, backups will be encrypted
    | using Laravel's encryption system before storage.
    |
    */

    'encrypt' => env('BACKUP_ENCRYPT', true),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | The encryption key used for backup encryption. Defaults to app key.
    | For production, use a separate backup encryption key.
    |
    */

    'encryption_key' => env('BACKUP_ENCRYPTION_KEY', env('APP_KEY')),

    /*
    |--------------------------------------------------------------------------
    | Off-Site Storage Disk
    |--------------------------------------------------------------------------
    |
    | The filesystem disk to use for off-site backup storage.
    | Options: s3, ftp, sftp, or any configured disk in filesystems.php
    |
    */

    'offsite_disk' => env('BACKUP_OFFSITE_DISK', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Backup Retention
    |--------------------------------------------------------------------------
    |
    | Number of days to keep backups before automatic cleanup.
    |
    */

    'retention_days' => env('BACKUP_RETENTION_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Backup Schedule
    |--------------------------------------------------------------------------
    |
    | Schedule for automatic backups. Default is daily at 2:00 AM.
    | Format: 'daily', 'weekly', or cron expression
    |
    */

    'schedule' => env('BACKUP_SCHEDULE', 'daily'),
    'schedule_time' => env('BACKUP_SCHEDULE_TIME', '02:00'),

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Email address to notify on backup failures.
    |
    */

    'notification_email' => env('BACKUP_NOTIFICATION_EMAIL', null),
];

