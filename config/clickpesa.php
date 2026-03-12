<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ClickPesa API Configuration
    |--------------------------------------------------------------------------
    |
    | This array contains the configuration settings for the ClickPesa API integration.
    | You can obtain these credentials from your ClickPesa merchant dashboard.
    |
    */

    'api_url' => env('CLICKPESA_API_URL', 'https://api.clickpesa.com/v1'),
    'api_key' => env('CLICKPESA_API_KEY'),
    'api_secret' => env('CLICKPESA_API_SECRET'),
    'merchant_id' => env('CLICKPESA_MERCHANT_ID'),
    'callback_url' => env('CLICKPESA_CALLBACK_URL'),
    'return_url' => env('CLICKPESA_RETURN_URL'),
    'cancel_url' => env('CLICKPESA_CANCEL_URL'),

    /*
    |--------------------------------------------------------------------------
    | Token Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for token-based authentication with ClickPesa API.
    |
    */
    'token' => [
        'cache_ttl' => env('CLICKPESA_TOKEN_CACHE_TTL', 3600), // 1 hour
        'cache_key' => env('CLICKPESA_TOKEN_CACHE_KEY', 'clickpesa_token'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    |
    | Configuration for different payment methods supported by ClickPesa.
    |
    */
    'payment_methods' => [
        'ussd' => [
            'enabled' => env('CLICKPESA_USSD_ENABLED', true),
            'prefix' => env('CLICKPESA_USSD_PREFIX', '*149*01#'),
        ],
        'card' => [
            'enabled' => env('CLICKPESA_CARD_ENABLED', true),
            '3ds_required' => env('CLICKPESA_CARD_3DS_REQUIRED', true),
        ],
        'mobile' => [
            'enabled' => env('CLICKPESA_MOBILE_ENABLED', true),
            'providers' => ['tigo', 'vodacom', 'airtel', 'halotel'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for handling ClickPesa webhooks and callbacks.
    |
    */
    'webhook' => [
        'secret' => env('CLICKPESA_WEBHOOK_SECRET'),
        'middleware' => ['api', 'throttle:60,1'],
        'routes' => [
            'callback' => 'clickpesa.callback',
            'webhook' => 'clickpesa.webhook',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure logging for ClickPesa API requests and responses.
    |
    */
    'logging' => [
        'enabled' => env('CLICKPESA_LOGGING_ENABLED', true),
        'channel' => env('CLICKPESA_LOG_CHANNEL', 'clickpesa'),
        'level' => env('CLICKPESA_LOG_LEVEL', 'info'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sandbox Mode
    |--------------------------------------------------------------------------
    |
    | Enable sandbox mode for testing purposes.
    |
    */
    'sandbox' => env('CLICKPESA_SANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | Default currency for transactions.
    |
    */
    'default_currency' => env('CLICKPESA_DEFAULT_CURRENCY', 'TZS'),

    /*
    |--------------------------------------------------------------------------
    | Timeout Configuration
    |--------------------------------------------------------------------------
    |
    | Request timeout settings for API calls.
    |
    */
    'timeout' => [
        'connect' => env('CLICKPESA_CONNECT_TIMEOUT', 30),
        'request' => env('CLICKPESA_REQUEST_TIMEOUT', 60),
    ],
];
