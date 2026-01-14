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
     * 
     * ULID Format: 26 characters, Crockford's Base32 encoded
     * Example: 01ARZ3NDEKTSV4RRFFQ69G5FAV
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add ULID column as unique public identifier
            $table->string('ulid', 26)->unique()->nullable()->after('id');
        });

        // Generate ULIDs for existing users
        DB::table('users')->whereNull('ulid')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['ulid' => (string) new Ulid()]);
            }
        });

        // Make ULID non-nullable after populating
        Schema::table('users', function (Blueprint $table) {
            $table->string('ulid', 26)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['ulid']);
            $table->dropColumn('ulid');
        });
    }
};
