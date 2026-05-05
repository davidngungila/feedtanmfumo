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
        Schema::create('loan_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_token')->unique();
            $table->integer('current_step')->default(1);
            $table->boolean('completed')->default(false);
            $table->json('step_data')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('principal_amount', 15, 2)->nullable();
            $table->string('loan_type')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_registrations');
    }
};
