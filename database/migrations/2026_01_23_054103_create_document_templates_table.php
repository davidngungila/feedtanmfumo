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
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['pdf', 'email', 'report', 'certificate'])->default('pdf');
            $table->string('category')->nullable(); // loan_agreement, statement, etc.
            $table->text('content'); // HTML or template content
            $table->json('variables')->nullable(); // Available variables
            $table->json('settings')->nullable(); // Page size, margins, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['type', 'category']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
