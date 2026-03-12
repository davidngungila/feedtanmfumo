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
        Schema::table('guarantor_assessments', function (Blueprint $table) {
            // Add new fields that don't exist
            if (!Schema::hasColumn('guarantor_assessments', 'member_code')) {
                $table->string('member_code')->nullable()->after('guarantor_id');
            }
            if (!Schema::hasColumn('guarantor_assessments', 'full_name')) {
                $table->string('full_name')->nullable()->after('member_code');
            }
            if (!Schema::hasColumn('guarantor_assessments', 'phone')) {
                $table->string('phone')->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('guarantor_assessments', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('guarantor_assessments', 'address')) {
                $table->text('address')->nullable()->after('relationship_other');
            }
            if (!Schema::hasColumn('guarantor_assessments', 'occupation')) {
                $table->string('occupation')->nullable()->after('address');
            }
            if (!Schema::hasColumn('guarantor_assessments', 'monthly_income')) {
                $table->decimal('monthly_income', 10, 2)->nullable()->after('occupation');
            }
            
            // Add new assessment fields
            if (!Schema::hasColumn('guarantor_assessments', 'repayment_history')) {
                $table->string('repayment_history')->nullable()->after('monthly_income');
            }
            if (!Schema::hasColumn('guarantor_assessments', 'existing_debts')) {
                $table->string('existing_debts')->nullable()->after('repayment_history');
            }
            if (!Schema::hasColumn('guarantor_assessments', 'recovery_process')) {
                $table->string('recovery_process')->nullable()->after('existing_debts');
            }
            if (!Schema::hasColumn('guarantor_assessments', 'voluntary_guarantee')) {
                $table->string('voluntary_guarantee')->nullable()->after('recovery_process');
            }
            
            // Add tracking fields
            if (!Schema::hasColumn('guarantor_assessments', 'assessment_date')) {
                $table->timestamp('assessment_date')->nullable()->after('additional_comments');
            }
            if (!Schema::hasColumn('guarantor_assessments', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('assessment_date');
            }
            if (!Schema::hasColumn('guarantor_assessments', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guarantor_assessments', function (Blueprint $table) {
            //
        });
    }
};
