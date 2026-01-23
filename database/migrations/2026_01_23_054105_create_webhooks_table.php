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
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->enum('method', ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])->default('POST');
            $table->json('events')->nullable(); // Which events trigger this webhook
            $table->json('headers')->nullable(); // Custom headers
            $table->string('secret')->nullable(); // Webhook secret for verification
            $table->boolean('is_active')->default(true);
            $table->integer('timeout_seconds')->default(30);
            $table->integer('retry_attempts')->default(3);
            $table->timestamps();
            
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhooks');
    }
};
