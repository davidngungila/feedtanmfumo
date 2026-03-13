<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ClickPesa API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for ClickPesa payment gateway
    | integration. Update these values with your ClickPesa credentials.
    |
    */

    'api_url' => env('CLICKPESA_API_URL', 'https://api.clickpesa.com'),
    
    'api_token' => env('CLICKPESA_API_TOKEN', ''),
    
    'secret_key' => env('CLICKPESA_SECRET_KEY', ''),
    
    'checksum_enabled' => env('CLICKPESA_CHECKSUM_ENABLED', false),
    
    'timeout' => env('CLICKPESA_TIMEOUT', 30),
    
    'retry_attempts' => env('CLICKPESA_RETRY_ATTEMPTS', 3),
    
    'webhook_secret' => env('CLICKPESA_WEBHOOK_SECRET', ''),
    
    'default_currency' => env('CLICKPESA_DEFAULT_CURRENCY', 'TZS'),
    
    'supported_currencies' => [
        'TZS' => 'Tanzanian Shilling',
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
        'GBP' => 'British Pound',
    ],
    
    'payment_methods' => [
        'ussd_push' => [
            'name' => 'USSD Push',
            'enabled' => true,
            'min_amount' => 100,
            'max_amount' => 10000000,
        ],
        'card_payment' => [
            'name' => 'Card Payment',
            'enabled' => true,
            'min_amount' => 100,
            'max_amount' => 50000000,
        ],
        'bank_transfer' => [
            'name' => 'Bank Transfer',
            'enabled' => true,
            'min_amount' => 1000,
            'max_amount' => 100000000,
        ],
        'mobile_money' => [
            'name' => 'Mobile Money',
            'enabled' => true,
            'min_amount' => 100,
            'max_amount' => 5000000,
        ],
    ],
    
    'providers' => [
        'tigo-pesa' => 'Tigo Pesa',
        'm-pesa' => 'M-Pesa',
        'airtel-money' => 'Airtel Money',
        'halo-pesa' => 'Halo Pesa',
        'ezypesa' => 'EzyPesa',
    ],
    
    'endpoints' => [
        'generate_token' => '/auth/token',
        'preview_ussd_push' => '/third-parties/payments/preview-ussd-push-request',
        'initiate_ussd_push' => '/third-parties/payments/initiate-ussd-push-request',
        'process_card_payment' => '/third-parties/payments/process-card-payment',
        'process_payout' => '/third-parties/payments/process-payout',
        'get_transaction' => '/third-parties/payments/transaction/{id}',
        'refund_transaction' => '/third-parties/payments/refund',
    ],
    
    'webhooks' => [
        'payment_success' => '/webhooks/clickpesa/payment-success',
        'payment_failed' => '/webhooks/clickpesa/payment-failed',
        'payment_pending' => '/webhooks/clickpesa/payment-pending',
    ],
    
    'logging' => [
        'enabled' => env('CLICKPESA_LOGGING', true),
        'level' => env('CLICKPESA_LOG_LEVEL', 'info'),
        'channel' => env('CLICKPESA_LOG_CHANNEL', 'clickpesa'),
    ],
];
