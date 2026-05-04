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
        Schema::table('sms_providers', function (Blueprint $table) {
            $table->string('ulid', 26)->unique()->after('id');
            $table->index('ulid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_providers', function (Blueprint $table) {
            $table->dropIndex(['ulid']);
            $table->dropColumn('ulid');
        });
    }
};
