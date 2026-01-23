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
        Schema::create('password_policies', function (Blueprint $table) {
            $table->id();
            $table->integer('min_length')->default(8);
            $table->boolean('require_uppercase')->default(true);
            $table->boolean('require_lowercase')->default(true);
            $table->boolean('require_numbers')->default(true);
            $table->boolean('require_symbols')->default(false);
            $table->integer('max_age_days')->nullable(); // Password expiry
            $table->integer('min_age_days')->default(0); // Cannot change password within X days
            $table->integer('history_count')->default(0); // Remember last N passwords
            $table->integer('lockout_attempts')->default(5);
            $table->integer('lockout_duration_minutes')->default(30);
            $table->boolean('enforce_on_login')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_policies');
    }
};
