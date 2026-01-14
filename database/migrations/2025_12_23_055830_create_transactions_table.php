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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_number')->unique();
            $table->enum('transaction_type', ['loan_payment', 'loan_disbursement', 'savings_deposit', 'savings_withdrawal', 'investment_deposit', 'investment_disbursement', 'welfare_contribution', 'welfare_benefit']);
            $table->enum('related_type', ['loan', 'savings_account', 'investment', 'social_welfare'])->nullable();
            $table->foreignId('related_id')->nullable(); // Polymorphic reference
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['cash', 'mobile_money', 'bank_transfer', 'cheque'])->default('cash');
            $table->string('reference_number')->nullable();
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
