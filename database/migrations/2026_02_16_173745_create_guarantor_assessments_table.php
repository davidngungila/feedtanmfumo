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
        Schema::create('guarantor_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('ulid')->unique();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->foreignId('guarantor_id')->constrained('users')->onDelete('cascade');
            $table->string('borrower_name');
            $table->string('relationship');
            $table->string('relationship_other')->nullable();
            
            // Assessment fields
            $table->string('loan_purpose');
            $table->string('loan_purpose_other')->nullable();
            $table->string('reviewed_history'); // [Yes, I have reviewed... / Yes, but... / No...]
            $table->string('other_debts'); // [No / Yes, manageable / Yes, concerned / I don't know]
            
            // Financial Self-Assessment
            $table->string('sufficient_savings'); // [Yes, easily / Yes, but... / No]
            $table->string('financial_obligation_impact'); // [No / Yes, temporarily / Yes, severely]
            $table->string('other_guarantees'); // [No / Yes, for 1... / Yes, for 2+]
            
            // Understanding & Contingency
            $table->string('solely_responsible_understanding'); // [I fully understand / I need clarification]
            $table->string('recovery_mechanism_understanding'); // [I fully understand / I need clarification]
            $table->string('borrower_backup_plan'); // [Yes, reasonable / Yes, vague / No]
            $table->text('guarantor_backup_plan');
            
            // Declaration
            $table->boolean('final_declaration')->default(false);
            $table->text('additional_comments')->nullable();
            
            $table->string('status')->default('pending'); // pending, approved, clarification_needed
            $table->timestamp('submitted_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantor_assessments');
    }
};
