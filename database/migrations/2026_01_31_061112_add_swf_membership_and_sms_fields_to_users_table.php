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
            // SWF Membership fields
            if (!Schema::hasColumn('users', 'swf_member')) {
                $table->boolean('swf_member')->default(false)->after('membership_status');
            }
            if (!Schema::hasColumn('users', 'swf_number')) {
                $table->string('swf_number')->nullable()->after('swf_member');
            }
            
            // SMS Consent and preferences
            if (!Schema::hasColumn('users', 'sms_consent')) {
                $table->boolean('sms_consent')->default(true)->after('swf_number');
            }
            if (!Schema::hasColumn('users', 'preferred_language')) {
                $table->enum('preferred_language', ['sw', 'en'])->default('sw')->after('sms_consent');
            }
            
            // Birthday SMS tracking
            if (!Schema::hasColumn('users', 'last_birthday_sms_date')) {
                $table->date('last_birthday_sms_date')->nullable()->after('preferred_language');
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
                'swf_member',
                'swf_number',
                'sms_consent',
                'preferred_language',
                'last_birthday_sms_date',
            ]);
        });
    }
};
