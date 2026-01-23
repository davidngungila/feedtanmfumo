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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->string('model_type'); // App\Models\User, App\Models\Loan, etc.
            $table->enum('field_type', ['text', 'textarea', 'number', 'email', 'date', 'select', 'checkbox', 'radio', 'file'])->default('text');
            $table->json('options')->nullable(); // For select, radio, checkbox
            $table->text('default_value')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('validation_rules')->nullable();
            $table->text('help_text')->nullable();
            $table->timestamps();
            
            $table->index(['model_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
