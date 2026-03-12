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
        Schema::create('fia_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verification_id')->constrained('fia_verifications');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('payment_reference');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('payment_method');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('verified');
            $table->timestamp('verified_at');
            $table->foreignId('verified_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fia_payments');
    }
};
