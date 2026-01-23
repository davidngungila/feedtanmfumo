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
        Schema::create('sms_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Provider Name
            $table->string('username')->nullable(); // SMS Username
            $table->string('password')->nullable(); // SMS Password
            $table->string('from')->nullable(); // SMS From (Sender Name)
            $table->string('api_url'); // SMS API URL
            $table->text('description')->nullable(); // Description
            $table->boolean('active')->default(true); // Active status
            $table->boolean('is_primary')->default(false); // Set as Primary
            $table->timestamps();
            
            $table->index('active');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_providers');
    }
};
