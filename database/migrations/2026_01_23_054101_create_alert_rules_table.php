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
        Schema::create('alert_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('module'); // loans, savings, investments, etc.
            $table->string('condition'); // overdue, low_balance, etc.
            $table->json('conditions')->nullable(); // Detailed conditions
            $table->json('actions')->nullable(); // What to do when triggered
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->boolean('notify_admin')->default(true);
            $table->boolean('notify_user')->default(false);
            $table->timestamps();
            
            $table->index(['module', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_rules');
    }
};
