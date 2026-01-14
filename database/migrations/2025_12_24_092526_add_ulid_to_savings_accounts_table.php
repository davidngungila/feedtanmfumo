<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Ulid;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds ULID (Universally Unique Lexicographically Sortable Identifier)
     * as an opaque public identifier for financial system compliance.
     */
    public function up(): void
    {
        Schema::table('savings_accounts', function (Blueprint $table) {
            $table->string('ulid', 26)->unique()->nullable()->after('id');
        });

        // Generate ULIDs for existing savings accounts
        DB::table('savings_accounts')->whereNull('ulid')->chunkById(100, function ($accounts) {
            foreach ($accounts as $account) {
                DB::table('savings_accounts')
                    ->where('id', $account->id)
                    ->update(['ulid' => (string) new Ulid()]);
            }
        });

        // Make ULID non-nullable after populating
        Schema::table('savings_accounts', function (Blueprint $table) {
            $table->string('ulid', 26)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings_accounts', function (Blueprint $table) {
            $table->dropUnique(['ulid']);
            $table->dropColumn('ulid');
        });
    }
};
