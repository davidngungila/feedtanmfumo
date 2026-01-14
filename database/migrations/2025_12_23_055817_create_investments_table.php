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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('investment_number')->unique();
            $table->enum('plan_type', ['4_year', '6_year']);
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->decimal('expected_return', 15, 2);
            $table->decimal('profit_share', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('maturity_date');
            $table->date('disbursement_date')->nullable();
            $table->enum('status', ['active', 'matured', 'disbursed', 'cancelled'])->default('active');
            $table->boolean('maturity_alert_sent')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
