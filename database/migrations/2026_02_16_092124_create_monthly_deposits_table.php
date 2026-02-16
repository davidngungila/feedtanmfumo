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
        Schema::create('monthly_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('member_id');
            $table->string('name');
            $table->integer('month');
            $table->integer('year');
            $table->decimal('savings', 15, 2)->default(0);
            $table->decimal('shares', 15, 2)->default(0);
            $table->decimal('welfare', 15, 2)->default(0);
            $table->decimal('loan_principal', 15, 2)->default(0);
            $table->decimal('loan_interest', 15, 2)->default(0);
            $table->decimal('fine_penalty', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Add index for faster lookups
            $table->index(['member_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_deposits');
    }
};
