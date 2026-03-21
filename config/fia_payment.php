<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FIA Payment Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for the FIA Payment module.
    | The FIA Payment module handles payment confirmations and verifications
    | for the FeedTan Digital microfinance system.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | FIA Payment Settings
    |--------------------------------------------------------------------------
    */
    'fia_payment' => [
        // File upload settings
        'upload' => [
            'max_file_size' => 10240, // 10MB in KB
            'allowed_extensions' => ['xlsx', 'xls', 'csv'],
            'storage_path' => 'fia_payments',
        ],

        // Excel column mapping
        'excel_columns' => [
            'membership_code' => 'membership_code',
            'member_name' => 'member_name', 
            'reference_number' => 'reference_number',
            'amount' => 'amount',
            'payment_date' => 'payment_date',
            'notes' => 'notes',
            'payment_method' => 'payment_method',
            'bank_name' => 'bank_name',
            'account_number' => 'account_number',
        ],

        // Payment statuses
        'statuses' => [
            'pending' => 'Pending',
            'verified' => 'Verified', 
            'rejected' => 'Rejected',
        ],

        // Default values
        'defaults' => [
            'status' => 'pending',
            'payment_date_format' => 'Y-m-d',
            'currency' => 'TZS',
        ],

        // Validation rules
        'validation' => [
            'membership_code' => 'required|string|max:50',
            'member_name' => 'required|string|max:255',
            'reference_number' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
        ],

        // Pagination settings
        'pagination' => [
            'per_page' => 20,
            'max_per_page' => 100,
        ],

        // Export settings
        'export' => [
            'filename_prefix' => 'fia_payments',
            'date_format' => 'Y-m-d_H-i-s',
        ],

        // Email notifications
        'email' => [
            'enabled' => true,
            'to_address' => env('FIA_PAYMENT_EMAIL', 'admin@feedtan.com'),
            'subject_prefix' => '[FIA Payment]',
        ],

        // Auto-verification settings
        'auto_verification' => [
            'enabled' => false,
            'amount_threshold' => 100000, // Auto-verify payments below this amount
            'require_reference_match' => true,
        ],

        // Security settings
        'security' => [
            'require_csrf' => true,
            'rate_limit' => [
                'enabled' => true,
                'max_attempts' => 60,
                'decay_minutes' => 1,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Settings
    |--------------------------------------------------------------------------
    */
    'database' => [
        'table_name' => 'fia_payments',
        'connection' => env('DB_CONNECTION', 'mysql'),
        
        // Indexes for performance
        'indexes' => [
            'membership_code',
            'reference_number', 
            'status',
            'payment_date',
            'created_at',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    */
    'api' => [
        'version' => 'v1',
        'prefix' => 'api/fia-payments',
        'middleware' => ['api', 'throttle:60,1'],
        
        // Response format
        'response_format' => [
            'success_key' => 'success',
            'data_key' => 'data',
            'message_key' => 'message',
            'errors_key' => 'errors',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration Settings
    |--------------------------------------------------------------------------
    */
    'integrations' => [
        // ClickPesa integration (if needed)
        'clickpesa' => [
            'enabled' => false,
            'api_url' => env('CLICKPESA_API_URL'),
            'api_key' => env('CLICKPESA_API_KEY'),
            'webhook_secret' => env('CLICKPESA_WEBHOOK_SECRET'),
        ],

        // SMS notifications
        'sms' => [
            'enabled' => env('FIA_SMS_ENABLED', false),
            'provider' => env('FIA_SMS_PROVIDER', 'default'),
            'template_verification' => 'FIA payment verified: {amount} for {member_name}',
            'template_rejection' => 'FIA payment rejected: {amount} for {member_name}',
        ],

        // Email service
        'email' => [
            'enabled' => env('FIA_EMAIL_ENABLED', true),
            'template_verification' => 'emails.fia-payment-verified',
            'template_rejection' => 'emails.fia-payment-rejected',
            'template_upload' => 'emails.fia-payment-uploaded',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Settings
    |--------------------------------------------------------------------------
    */
    'ui' => [
        // Dashboard widgets
        'dashboard' => [
            'show_statistics' => true,
            'show_recent_activity' => true,
            'refresh_interval' => 30, // seconds
        ],

        // Table settings
        'table' => [
            'show_bulk_actions' => true,
            'show_export_button' => true,
            'show_filters' => true,
            'default_sort' => 'created_at',
            'default_order' => 'desc',
        ],

        // Form settings
        'form' => [
            'auto_save' => false,
            'confirmation_dialogs' => true,
            'progress_indicators' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Settings
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => true,
        'channel' => 'fia_payments',
        'level' => 'info',
        
        // Log events
        'events' => [
            'payment_uploaded' => true,
            'payment_verified' => true,
            'payment_rejected' => true,
            'bulk_operations' => true,
            'export_operations' => true,
            'errors' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => true,
        'prefix' => 'fia_payment',
        'ttl' => 3600, // 1 hour
        
        // Cache keys
        'keys' => [
            'statistics' => 'stats',
            'recent_payments' => 'recent',
            'member_payments' => 'member_{member_id}',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Development Settings
    |--------------------------------------------------------------------------
    */
    'development' => [
        'debug_mode' => env('APP_DEBUG', false),
        'mock_data' => env('FIA_MOCK_DATA', false),
        'test_mode' => env('FIA_TEST_MODE', false),
        
        // Sample data for testing
        'sample_data' => [
            'members' => [
                ['membership_code' => 'MEM001', 'name' => 'John Doe'],
                ['membership_code' => 'MEM002', 'name' => 'Jane Smith'],
                ['membership_code' => 'MEM003', 'name' => 'Bob Johnson'],
            ],
        ],
    ],
];
