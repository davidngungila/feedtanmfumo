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
        Schema::create('membership_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Founder, Ordinary, Promoter, Associate, Scholar, GROUP
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('entrance_fee')->default(0);
            $table->integer('capital_contribution')->default(0);
            $table->integer('minimum_shares')->default(0);
            $table->integer('maximum_shares')->nullable();
            $table->decimal('membership_interest_percentage', 5, 2)->default(0);
            $table->json('access_permissions')->nullable(); // Services access levels
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_types');
    }
};
