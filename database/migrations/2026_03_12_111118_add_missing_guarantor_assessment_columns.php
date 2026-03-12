<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('guarantor_assessments', function (Blueprint $table) {
            // Check and add missing columns one by one
            $columns = [
                'member_code' => 'string',
                'full_name' => 'string',
                'phone' => 'string',
                'email' => 'string',
                'address' => 'text',
                'occupation' => 'string',
                'monthly_income' => 'decimal(10,2)',
                'repayment_history' => 'string',
                'existing_debts' => 'string',
                'sole_responsibility' => 'string',
                'recovery_process' => 'string',
                'voluntary_guarantee' => 'string',
                'assessment_date' => 'timestamp',
                'ip_address' => 'string',
                'user_agent' => 'text',
                'status' => 'string',
                'submitted_at' => 'timestamp',
                'agreement_path' => 'string'
            ];

            foreach ($columns as $column => $type) {
                if (!Schema::hasColumn('guarantor_assessments', $column)) {
                    if ($type === 'text') {
                        $table->text($column)->nullable();
                    } elseif ($type === 'decimal(10,2)') {
                        $table->decimal('monthly_income', 10, 2)->nullable();
                    } elseif ($type === 'timestamp') {
                        $table->timestamp($column)->nullable();
                    } else {
                        $table->$type($column)->nullable();
                    }
                }
            }
        });
    }

    public function down()
    {
        Schema::table('guarantor_assessments', function (Blueprint $table) {
            $columns = [
                'member_code',
                'full_name',
                'phone',
                'email',
                'address',
                'occupation',
                'monthly_income',
                'repayment_history',
                'existing_debts',
                'sole_responsibility',
                'recovery_process',
                'voluntary_guarantee',
                'assessment_date',
                'ip_address',
                'user_agent',
                'status',
                'submitted_at',
                'agreement_path'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('guarantor_assessments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
