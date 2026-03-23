<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Email Configuration...\n\n";

// Check mail configuration
echo "Mail Configuration:\n";
echo "MAIL_MAILER: " . env('MAIL_MAILER', 'not set') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST', 'not set') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT', 'not set') . "\n";
echo "MAIL_USERNAME: " . (env('MAIL_USERNAME') ? 'set' : 'not set') . "\n";
echo "MAIL_PASSWORD: " . (env('MAIL_PASSWORD') ? 'set' : 'not set') . "\n";
echo "MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION', 'not set') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS', 'not set') . "\n";
echo "MAIL_FROM_NAME: " . env('MAIL_FROM_NAME', 'not set') . "\n\n";

// Test email sending
try {
    echo "Attempting to send test email...\n";
    
    Mail::raw('This is a test email from FIA Payment System.', function ($message) {
        $message->to('test@example.com')
                ->subject('FIA Email Test');
    });
    
    echo "✅ Email sent successfully!\n";
    
} catch (\Exception $e) {
    echo "❌ Email failed to send: " . $e->getMessage() . "\n";
    echo "Error details: " . $e->getTraceAsString() . "\n";
}

echo "\nChecking Laravel logs...\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    $recentLogs = substr($logs, -2000); // Last 2000 characters
    echo "Recent log entries:\n";
    echo $recentLogs . "\n";
} else {
    echo "No log file found.\n";
}

echo "\nEmail test completed.\n";
