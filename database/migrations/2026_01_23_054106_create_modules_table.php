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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('version')->default('1.0.0');
            $table->text('description')->nullable();
            $table->enum('status', ['enabled', 'disabled', 'installing', 'uninstalling'])->default('disabled');
            $table->json('dependencies')->nullable(); // Other modules this depends on
            $table->json('settings')->nullable(); // Module-specific settings
            $table->string('author')->nullable();
            $table->string('license')->nullable();
            $table->timestamps();
            
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
