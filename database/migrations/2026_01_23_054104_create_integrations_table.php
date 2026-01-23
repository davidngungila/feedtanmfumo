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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // payment_gateway, sms_provider, email_service, etc.
            $table->string('provider'); // stripe, paypal, twilio, etc.
            $table->json('credentials')->nullable(); // Encrypted API keys, tokens, etc.
            $table->json('settings')->nullable(); // Configuration options
            $table->boolean('is_active')->default(false);
            $table->boolean('is_test_mode')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
