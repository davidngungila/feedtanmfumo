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
        Schema::table('investments', function (Blueprint $table) {
            $table->string('investment_name')->after('investment_number')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->dropColumn('investment_name');
        });
    }
};
