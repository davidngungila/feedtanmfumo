<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_confirmations', function (Blueprint $table) {
            $table->enum('payment_method', ['bank', 'mobile'])->nullable()->after('notes');
            $table->enum('mobile_provider', ['mpesa', 'halotel'])->nullable()->after('payment_method');
            $table->string('mobile_number')->nullable()->after('mobile_provider');
            $table->string('bank_account_number')->nullable()->after('mobile_number');
            $table->string('bank_account_confirmation')->nullable()->after('bank_account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_confirmations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'mobile_provider',
                'mobile_number',
                'bank_account_number',
                'bank_account_confirmation',
            ]);
        });
    }
};
