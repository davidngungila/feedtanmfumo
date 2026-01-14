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
            // Member identification
            if (!Schema::hasColumn('users', 'member_number')) {
                $table->string('member_number')->unique()->nullable()->after('id');
            }
            
            // Status fields
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('pending')->after('role');
            }
            if (!Schema::hasColumn('users', 'status_changed_at')) {
                $table->timestamp('status_changed_at')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'status_reason')) {
                $table->text('status_reason')->nullable()->after('role');
            }
            
            // Personal information
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'national_id')) {
                $table->string('national_id')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'marital_status')) {
                $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable()->after('name');
            }
            
            // Contact information
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'alternate_phone')) {
                $table->string('alternate_phone')->nullable()->after('email');
            }
            
            // Address information
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'region')) {
                $table->string('region')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('email');
            }
            
            // Employment information
            if (!Schema::hasColumn('users', 'occupation')) {
                $table->string('occupation')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'employer')) {
                $table->string('employer')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'monthly_income')) {
                $table->decimal('monthly_income', 15, 2)->nullable()->after('email');
            }
            
            // KYC information
            if (!Schema::hasColumn('users', 'kyc_status')) {
                $table->enum('kyc_status', ['verified', 'pending', 'rejected', 'expired'])->default('pending')->after('email');
            }
            if (!Schema::hasColumn('users', 'kyc_expiry_date')) {
                $table->date('kyc_expiry_date')->nullable()->after('email');
            }
            
            // Grouping
            if (!Schema::hasColumn('users', 'group_id')) {
                $table->unsignedBigInteger('group_id')->nullable()->after('email');
            }
            
            // Additional notes
            if (!Schema::hasColumn('users', 'notes')) {
                $table->text('notes')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'member_number',
                'status',
                'status_changed_at',
                'status_reason',
                'date_of_birth',
                'gender',
                'national_id',
                'marital_status',
                'phone',
                'alternate_phone',
                'address',
                'city',
                'region',
                'postal_code',
                'occupation',
                'employer',
                'monthly_income',
                'kyc_status',
                'kyc_expiry_date',
                'group_id',
                'notes',
            ]);
        });
    }
};
