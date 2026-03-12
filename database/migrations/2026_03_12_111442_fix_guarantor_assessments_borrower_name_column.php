<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('guarantor_assessments', function (Blueprint $table) {
            // Handle borrower_name column - either make it nullable or provide default
            if (Schema::hasColumn('guarantor_assessments', 'borrower_name')) {
                $table->string('borrower_name')->nullable()->change();
            }
            
            // Also handle any other potentially problematic columns that might exist
            $problematicColumns = [
                'borrower_backup_plan',
                'guarantor_backup_plan', 
                'final_declaration',
                'understands_responsibility',
                'financial_obligation_impact',
                'other_guarantees',
                'reviewed_history',
                'other_debts',
                'solely_responsible_understanding',
                'recovery_mechanism_understanding'
            ];
            
            foreach ($problematicColumns as $column) {
                if (Schema::hasColumn('guarantor_assessments', $column)) {
                    $table->string($column)->nullable()->change();
                }
            }
        });
    }

    public function down()
    {
        Schema::table('guarantor_assessments', function (Blueprint $table) {
            // This migration is just making columns nullable, so down() doesn't need to do much
            // The columns will remain nullable which is safe
        });
    }
};
