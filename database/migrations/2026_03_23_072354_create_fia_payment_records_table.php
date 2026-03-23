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
        Schema::create('fia_payment_records', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->nullable(); // S/N
            $table->string('member_id'); // ID
            $table->string('member_name'); // NAME
            $table->decimal('gawio_la_fia', 15, 2)->default(0); // Gawio la FIA
            $table->decimal('fia_iliyokomaa', 15, 2)->default(0); // FIA iliyokomaa
            $table->decimal('jumla', 15, 2)->default(0); // Jumla
            $table->decimal('malipo_ya_vipande', 15, 2)->default(0); // Malipo ya vipande yailiyakuwa Yamepelea
            $table->decimal('loan', 15, 2)->default(0); // LOAN
            $table->decimal('kiasi_baki', 15, 2)->default(0); // Kiasi baki
            $table->string('upload_filename')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('member_id');
            $table->index('member_name');
            $table->index('upload_filename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fia_payment_records');
    }
};
