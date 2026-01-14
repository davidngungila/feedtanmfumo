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
        Schema::table('social_welfares', function (Blueprint $table) {
            $table->string('ulid', 26)->unique()->nullable()->after('id');
        });

        // Generate ULIDs for existing social welfare records
        DB::table('social_welfares')->whereNull('ulid')->chunkById(100, function ($welfares) {
            foreach ($welfares as $welfare) {
                DB::table('social_welfares')
                    ->where('id', $welfare->id)
                    ->update(['ulid' => (string) new Ulid()]);
            }
        });

        // Make ULID non-nullable after populating
        Schema::table('social_welfares', function (Blueprint $table) {
            $table->string('ulid', 26)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_welfares', function (Blueprint $table) {
            $table->dropUnique(['ulid']);
            $table->dropColumn('ulid');
        });
    }
};
