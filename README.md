# ClickPesa Laravel Package

A comprehensive Laravel integration package for ClickPesa API that provides seamless payment processing capabilities including token authorization, USSD checkout, card payments, payment status queries, and wallet balance retrieval.

## Features

- 🔐 **Token Authorization** - Automatic token management with caching
- 📱 **USSD Checkout** - Initiate USSD-based payments
- 💳 **Card Payments** - Process credit/debit card payments with 3DS support
- 📊 **Payment Status Queries** - Check single or multiple payment statuses
- 💰 **Wallet Balance** - Retrieve wallet balance information
- 🔗 **Payment Links** - Create shareable payment links
- 🔄 **Webhook Handling** - Process payment webhooks securely
- 📝 **Transaction History** - Retrieve detailed transaction records
- 💸 **Refunds** - Process payment refunds
- 🎯 **Events & Listeners** - Laravel event system integration

## Installation

1. Install the package via Composer:
```bash
composer require feedtan/clickpesa-laravel
```

2. Publish the configuration and migrations:
```bash
php artisan clickpesa:install
```

3. Add environment variables to your `.env` file:
```env
CLICKPESA_API_URL=https://api.clickpesa.com/v1
CLICKPESA_API_KEY=your_api_key
CLICKPESA_API_SECRET=your_api_secret
CLICKPESA_MERCHANT_ID=your_merchant_id
CLICKPESA_CALLBACK_URL=https://your-app.com/clickpesa/callback
CLICKPESA_RETURN_URL=https://your-app.com/payment/success
CLICKPESA_CANCEL_URL=https://your-app.com/payment/cancel
CLICKPESA_WEBHOOK_SECRET=your_webhook_secret
CLICKPESA_SANDBOX=false
```

4. Run the migrations:
```bash
php artisan migrate
```

## Configuration

The package configuration file is published at `config/clickpesa.php`. Key configuration options:

- `api_url` - ClickPesa API endpoint
- `api_key` - Your ClickPesa API key
- `api_secret` - Your ClickPesa API secret
- `merchant_id` - Your merchant ID
- `callback_url` - URL for payment callbacks
- `return_url` - URL for successful returns
- `cancel_url` - URL for cancelled payments
- `webhook.secret` - Secret for webhook signature validation
- `sandbox` - Enable sandbox mode for testing

## Usage

### Basic Usage

```php
use FeedTan\ClickPesa\Facades\ClickPesa;

// USSD Checkout
$response = ClickPesa::initiateUssdCheckout([
    'amount' => 10000,
    'phone_number' => '+255712345678',
    'currency' => 'TZS',
    'reference' => 'INV-001',
    'description' => 'Payment for invoice #001',
]);

// Card Payment
$response = ClickPesa::initiateCardPayment([
    'amount' => 10000,
    'card_number' => '4111111111111111',
    'card_expiry' => '1225',
    'card_cvv' => '123',
    'card_holder' => 'John Doe',
    'currency' => 'TZS',
    'reference' => 'INV-001',
]);

// Mobile Money Payment
$response = ClickPesa::initiateMobilePayment([
    'amount' => 10000,
    'phone_number' => '+255712345678',
    'provider' => 'tigo',
    'currency' => 'TZS',
    'reference' => 'INV-001',
]);
```

### Payment Status

```php
// Query single payment
$status = ClickPesa::queryPaymentStatus('TXN123456789');

// Query multiple payments
$statuses = ClickPesa::queryPaymentStatuses(['TXN123', 'TXN456', 'TXN789']);
```

### Wallet Balance

```php
// Get merchant wallet balance
$balance = ClickPesa::getWalletBalance();

// Get specific wallet balance
$balance = ClickPesa::getWalletBalance('WALLET123');
```

### Payment Links

```php
// Create payment link
$link = ClickPesa::createPaymentLink([
    'amount' => 10000,
    'currency' => 'TZS',
    'reference' => 'INV-001',
    'description' => 'Payment for invoice #001',
    'customer_email' => 'customer@example.com',
    'expiry_hours' => 24,
]);

// Get payment link details
$details = ClickPesa::getPaymentLink('LINK123456');
```

### Transaction History

```php
// Get transaction history with filters
$history = ClickPesa::getTransactionHistory([
    'start_date' => '2024-01-01',
    'end_date' => '2024-01-31',
    'status' => 'completed',
    'payment_method' => 'ussd',
    'limit' => 50,
]);
```

### Refunds

```php
// Refund a payment
$refund = ClickPesa::refundPayment('TXN123456789', [
    'amount' => 5000,
    'reason' => 'Customer requested refund',
    'reference' => 'REF-001',
]);
```

## Webhook Handling

Add the webhook routes to your `routes/web.php` or `routes/api.php`:

```php
Route::middleware(['api', 'throttle:60,1'])->group(function () {
    Route::post('/clickpesa/webhook', [\FeedTan\ClickPesa\Http\Controllers\WebhookController::class, 'handle']);
    Route::post('/clickpesa/callback', [\FeedTan\ClickPesa\Http\Controllers\WebhookController::class, 'callback']);
});
```

### Event Listeners

Create event listeners to handle payment events:

```php
// App\Providers\EventServiceProvider.php
protected $listen = [
    \FeedTan\ClickPesa\Events\PaymentCompleted::class => [
        'App\Listeners\HandlePaymentCompleted',
    ],
    \FeedTan\ClickPesa\Events\PaymentFailed::class => [
        'App\Listeners\HandlePaymentFailed',
    ],
];
```

Example listener:

```php
namespace App\Listeners;

use FeedTan\ClickPesa\Events\PaymentCompleted;

class HandlePaymentCompleted
{
    public function handle(PaymentCompleted $event)
    {
        $transaction = $event->transaction;
        $webhookData = $event->webhookData;
        
        // Handle successful payment
        // Update order status, send confirmation email, etc.
    }
}
```

## Database Tables

The package creates the following database tables:

- `clickpesa_transactions` - Stores all payment transactions
- `clickpesa_payment_links` - Stores payment link information
- `clickpesa_webhooks` - Stores received webhooks for audit

## Security

- **Webhook Signature Validation** - All incoming webhooks are validated using HMAC-SHA256
- **Token Caching** - Authentication tokens are cached to reduce API calls
- **Request Logging** - All API requests and responses are logged
- **Input Validation** - All inputs are validated before API calls

## Error Handling

The package throws exceptions for various scenarios:

- `\InvalidArgumentException` - Validation failures
- `\Exception` - API errors, network issues, etc.

Always wrap API calls in try-catch blocks:

```php
try {
    $response = ClickPesa::initiateUssdCheckout($data);
    // Handle success
} catch (\InvalidArgumentException $e) {
    // Handle validation error
} catch (\Exception $e) {
    // Handle API error
}
```

## Logging

All API requests and responses are logged to the configured log channel. Configure logging in `config/logging.php`:

```php
'channels' => [
    'clickpesa' => [
        'driver' => 'daily',
        'path' => storage_path('logs/clickpesa.log'),
        'level' => 'info',
    ],
],
```

## Testing

Enable sandbox mode for testing:

```env
CLICKPESA_SANDBOX=true
```

## Support

For support and issues, please visit the package repository or contact FeedTan CMG.

## License

This package is open-sourced software licensed under the MIT license.
