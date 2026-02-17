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
        Schema::table('payment_confirmations', function (Blueprint $table) {
            $table->string('mobile_provider')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_confirmations', function (Blueprint $table) {
            $table->enum('mobile_provider', ['mpesa', 'halotel'])->nullable()->change();
        });
    }
};
