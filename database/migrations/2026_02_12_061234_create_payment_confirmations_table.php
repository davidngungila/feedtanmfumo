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
        Schema::create('payment_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('member_id');
            $table->string('member_name');
            $table->string('member_type')->nullable();
            $table->decimal('amount_to_pay', 15, 2);
            $table->decimal('deposit_balance', 15, 2);
            $table->decimal('swf_contribution', 15, 2)->default(0);
            $table->decimal('re_deposit', 15, 2)->default(0);
            $table->decimal('fia_investment', 15, 2)->default(0);
            $table->string('fia_type')->nullable()->comment('4_year or 6_year');
            $table->decimal('capital_contribution', 15, 2)->default(0);
            $table->decimal('loan_repayment', 15, 2)->default(0);
            $table->string('member_email');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_confirmations');
    }
};
