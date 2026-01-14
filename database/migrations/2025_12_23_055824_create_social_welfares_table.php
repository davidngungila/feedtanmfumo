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
        Schema::create('social_welfares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('welfare_number')->unique();
            $table->enum('type', ['contribution', 'benefit']); // Contribution or Benefit disbursement
            $table->enum('benefit_type', ['medical', 'funeral', 'educational', 'other'])->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->enum('status', ['pending', 'approved', 'disbursed', 'rejected'])->default('pending');
            $table->text('description')->nullable();
            $table->text('eligibility_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('approval_date')->nullable();
            $table->date('disbursement_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_welfares');
    }
};
