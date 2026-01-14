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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('membership_type_id')->nullable()->after('member_number')->constrained('membership_types')->onDelete('set null');
            $table->enum('membership_status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending')->after('membership_type_id');
            $table->string('membership_code')->nullable()->after('membership_status');
            $table->integer('number_of_shares')->default(0)->after('membership_code');
            $table->decimal('entrance_fee', 15, 2)->default(0)->after('number_of_shares');
            $table->decimal('capital_contribution', 15, 2)->default(0)->after('entrance_fee');
            $table->decimal('capital_outstanding', 15, 2)->default(0)->after('capital_contribution');
            $table->decimal('membership_interest_percentage', 5, 2)->default(0)->after('capital_outstanding');
            $table->string('bank_name')->nullable()->after('membership_interest_percentage');
            $table->string('bank_branch')->nullable()->after('bank_name');
            $table->string('bank_account_number')->nullable()->after('bank_branch');
            $table->text('short_bibliography')->nullable()->after('bank_account_number');
            $table->string('introduced_by')->nullable()->after('short_bibliography');
            $table->string('guarantor_name')->nullable()->after('introduced_by');
            $table->text('beneficiaries_info')->nullable()->after('guarantor_name'); // JSON: names, relationship, allocation, bank details, contact
            $table->string('application_letter_path')->nullable()->after('beneficiaries_info');
            $table->string('payment_slip_path')->nullable()->after('application_letter_path');
            $table->string('standing_order_path')->nullable()->after('payment_slip_path');
            $table->string('nida_picture_path')->nullable()->after('standing_order_path');
            $table->string('passport_picture_path')->nullable()->after('nida_picture_path');
            $table->string('payment_reference_number')->nullable()->after('passport_picture_path');
            $table->enum('statement_preference', ['email', 'sms', 'postal'])->default('email')->after('payment_reference_number');
            $table->boolean('is_group_registered')->default(false)->after('statement_preference');
            $table->string('group_name')->nullable()->after('is_group_registered');
            $table->text('group_leaders')->nullable()->after('group_name');
            $table->string('group_bank_account')->nullable()->after('group_leaders');
            $table->text('group_contacts')->nullable()->after('group_bank_account');
            $table->boolean('wants_ordinary_membership')->default(false)->after('group_contacts');
            $table->timestamp('membership_approved_at')->nullable()->after('wants_ordinary_membership');
            $table->foreignId('membership_approved_by')->nullable()->after('membership_approved_at')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['membership_type_id']);
            $table->dropForeign(['membership_approved_by']);
            $table->dropColumn([
                'membership_type_id',
                'membership_status',
                'membership_code',
                'number_of_shares',
                'entrance_fee',
                'capital_contribution',
                'capital_outstanding',
                'membership_interest_percentage',
                'bank_name',
                'bank_branch',
                'bank_account_number',
                'short_bibliography',
                'introduced_by',
                'guarantor_name',
                'beneficiaries_info',
                'application_letter_path',
                'payment_slip_path',
                'standing_order_path',
                'nida_picture_path',
                'passport_picture_path',
                'payment_reference_number',
                'statement_preference',
                'is_group_registered',
                'group_name',
                'group_leaders',
                'group_bank_account',
                'group_contacts',
                'wants_ordinary_membership',
                'membership_approved_at',
                'membership_approved_by',
            ]);
        });
    }
};
