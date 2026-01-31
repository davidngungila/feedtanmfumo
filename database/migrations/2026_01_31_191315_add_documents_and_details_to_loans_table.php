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
        Schema::table('loans', function (Blueprint $table) {
            // Additional loan details
            $table->string('loan_type')->nullable()->after('purpose'); // e.g., Personal, Business, Agricultural
            $table->text('collateral_description')->nullable()->after('loan_type');
            $table->decimal('collateral_value', 15, 2)->nullable()->after('collateral_description');
            $table->string('guarantor_name')->nullable()->after('collateral_value');
            $table->string('guarantor_phone')->nullable()->after('guarantor_name');
            $table->string('guarantor_email')->nullable()->after('guarantor_phone');
            $table->text('guarantor_address')->nullable()->after('guarantor_email');
            $table->text('business_plan')->nullable()->after('guarantor_address');
            $table->text('repayment_source')->nullable()->after('business_plan');
            $table->text('additional_notes')->nullable()->after('repayment_source');
            
            // Document fields
            $table->string('application_document')->nullable()->after('additional_notes'); // Main application document
            $table->json('supporting_documents')->nullable()->after('application_document'); // Array of document paths
            $table->string('id_document')->nullable()->after('supporting_documents');
            $table->string('proof_of_income')->nullable()->after('id_document');
            $table->string('collateral_document')->nullable()->after('proof_of_income');
            $table->string('guarantor_document')->nullable()->after('collateral_document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'loan_type',
                'collateral_description',
                'collateral_value',
                'guarantor_name',
                'guarantor_phone',
                'guarantor_email',
                'guarantor_address',
                'business_plan',
                'repayment_source',
                'additional_notes',
                'application_document',
                'supporting_documents',
                'id_document',
                'proof_of_income',
                'collateral_document',
                'guarantor_document',
            ]);
        });
    }
};
