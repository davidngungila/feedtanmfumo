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
        Schema::create('fia_admin_payments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('fia_gawio', 10, 2)->nullable();
            $table->decimal('fia_iliyokomaa', 10, 2)->nullable();
            $table->decimal('jumla', 10, 2)->nullable();
            $table->decimal('malipo_vipande', 10, 2)->nullable();
            $table->decimal('loan', 10, 2)->nullable();
            $table->decimal('kiasi_baki', 10, 2)->nullable();
            $table->string('membership_code')->unique();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fia_admin_payments');
    }
};
