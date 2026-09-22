<?php

use Spatie\Backup\Notifications\Notifiable;
use Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification;
use Spatie\Backup\Notifications\Notifications\BackupWasSuccessfulNotification;
use Spatie\Backup\Notifications\Notifications\CleanupHasFailedNotification;
use Spatie\Backup\Notifications\Notifications\CleanupWasSuccessfulNotification;
use Spatie\Backup\Notifications\Notifications\HealthyBackupWasFoundNotification;
use Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFoundNotification;
use Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy;
use Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays;
use Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes;

return [
    'backup' => [
        'name' => env('APP_NAME', 'waggies'),
        'source' => [
            'files' => [
                'include' => [base_path()],
                'exclude' => [
                    base_path('.env'),
                    base_path('vendor'),
                    base_path('node_modules'),
                    base_path('storage/framework'),
                    base_path('storage/logs'),
                    base_path('storage/app/backups'),
                    base_path('storage/app/backup-temp'),
                    base_path('database/database.sqlite'),
                ],
                'follow_links' => false,
                'ignore_unreadable_directories' => false,
                'relative_path' => null,
            ],
            'databases' => [env('DB_CONNECTION', 'sqlite')],
        ],
        'database_dump_compressor' => null,
        'database_dump_file_timestamp_format' => null,
        'database_dump_filename_base' => 'database',
        'database_dump_file_extension' => '',
        'destination' => [
            'compression_method' => ZipArchive::CM_DEFAULT,
            'compression_level' => 9,
            'filename_prefix' => '',
            'disks' => [env('BACKUP_DISK', 'backups')],
            'continue_on_failure' => false,
        ],
        'temporary_directory' => storage_path('app/backup-temp'),
        'password' => env('BACKUP_ARCHIVE_PASSWORD'),
        'encryption' => env('BACKUP_ARCHIVE_ENCRYPTION', 'default'),
        'verify_backup' => (bool) env('BACKUP_VERIFY', true),
        'tries' => (int) env('BACKUP_TRIES', 1),
        'retry_delay' => (int) env('BACKUP_RETRY_DELAY', 0),
    ],
    'notifications' => [
        'notifications' => [
            BackupHasFailedNotification::class => [],
            UnhealthyBackupWasFoundNotification::class => [],
            CleanupHasFailedNotification::class => [],
            BackupWasSuccessfulNotification::class => [],
            HealthyBackupWasFoundNotification::class => [],
            CleanupWasSuccessfulNotification::class => [],
        ],
        'notifiable' => Notifiable::class,
        'mail' => [
            'to' => env('BACKUP_NOTIFICATION_EMAIL') ?: env('MAIL_FROM_ADDRESS', 'hello@example.com'),
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Waggies'),
            ],
        ],
    ],
    'log_channel' => env('BACKUP_LOG_CHANNEL', 'stack'),
    'monitor_backups' => [
        [
            'name' => env('APP_NAME', 'waggies'),
            'disks' => [env('BACKUP_DISK', 'backups')],
            'health_checks' => [
                MaximumAgeInDays::class => (int) env('BACKUP_MAX_AGE_DAYS', 2),
                MaximumStorageInMegabytes::class => (int) env('BACKUP_MAX_SIZE_MB', 5000),
            ],
        ],
    ],
    'cleanup' => [
        'strategy' => DefaultStrategy::class,
        'default_strategy' => [
            'keep_all_backups_for_days' => 7,
            'keep_daily_backups_for_days' => 16,
            'keep_weekly_backups_for_weeks' => 8,
            'keep_monthly_backups_for_months' => 4,
            'keep_yearly_backups_for_years' => 2,
            'delete_oldest_backups_when_using_more_megabytes_than' => (int) env('BACKUP_MAX_SIZE_MB', 5000),
        ],
        'tries' => 1,
        'retry_delay' => 0,
    ],
];
