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
        Schema::create('clickpesa_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('reference');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('payment_method');
            $table->string('status')->default('pending');
            $table->string('phone_number')->nullable();
            $table->string('card_last4')->nullable();
            $table->string('provider')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->string('callback_url')->nullable();
            $table->string('return_url')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['payment_method', 'created_at']);
            $table->index('phone_number');
        });

        Schema::create('clickpesa_payment_links', function (Blueprint $table) {
            $table->id();
            $table->string('link_id')->unique();
            $table->string('reference');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('status')->default('active');
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'expires_at']);
            $table->index('customer_email');
        });

        Schema::create('clickpesa_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('event_type');
            $table->string('transaction_id')->nullable();
            $table->text('payload');
            $table->string('signature');
            $table->boolean('processed')->default(false);
            $table->text('processing_error')->nullable();
            $table->timestamps();

            $table->index(['event_type', 'processed']);
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clickpesa_webhooks');
        Schema::dropIfExists('clickpesa_payment_links');
        Schema::dropIfExists('clickpesa_transactions');
    }
};
