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
        Schema::table('monthly_deposits', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
            $table->string('statement_pdf')->nullable()->after('total');
            $table->text('generated_message')->nullable()->after('statement_pdf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_deposits', function (Blueprint $table) {
            $table->dropColumn(['email', 'statement_pdf', 'generated_message']);
        });
    }
};
