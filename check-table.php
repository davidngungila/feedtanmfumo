<?php

require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Checking payment_confirmations table...\n\n";

try {
    // Check if table exists
    if (Schema::hasTable('payment_confirmations')) {
        echo "✅ Table 'payment_confirmations' exists\n\n";
        
        // Get table structure
        $columns = DB::select('DESCRIBE payment_confirmations');
        echo "Table structure:\n";
        foreach ($columns as $column) {
            echo "- {$column->Field} ({$column->Type})\n";
        }
        
        // Count records
        $count = DB::table('payment_confirmations')->count();
        echo "\n📊 Total records: {$count}\n";
        
        // Show recent records if any
        if ($count > 0) {
            echo "\n📋 Recent records:\n";
            $recent = DB::table('payment_confirmations')->orderBy('created_at', 'desc')->limit(5)->get();
            foreach ($recent as $record) {
                echo "- ID: {$record->id}, Member: {$record->member_id}, Amount: {$record->amount}, Status: {$record->status}\n";
            }
        }
        
    } else {
        echo "❌ Table 'payment_confirmations' does not exist\n";
        
        // Check if migration exists
        $migrationPath = database_path('migrations');
        $migrations = glob($migrationPath . '/*payment_confirmations*');
        
        if (!empty($migrations)) {
            echo "\n📁 Found migration files:\n";
            foreach ($migrations as $migration) {
                echo "- " . basename($migration) . "\n";
            }
            echo "\n💡 Run: php artisan migrate to create the table\n";
        } else {
            echo "\n❌ No migration found for payment_confirmations table\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\nDone.\n";
