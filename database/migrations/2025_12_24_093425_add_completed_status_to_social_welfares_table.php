<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds 'completed' status to the social_welfares status enum
     * This is needed for contribution records that are immediately completed
     */
    public function up(): void
    {
        // MySQL requires dropping and recreating the enum to modify it
        DB::statement("ALTER TABLE `social_welfares` MODIFY COLUMN `status` ENUM('pending', 'approved', 'disbursed', 'rejected', 'completed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'completed' from enum (but first need to update any 'completed' records)
        DB::statement("UPDATE `social_welfares` SET `status` = 'disbursed' WHERE `status` = 'completed'");
        DB::statement("ALTER TABLE `social_welfares` MODIFY COLUMN `status` ENUM('pending', 'approved', 'disbursed', 'rejected') DEFAULT 'pending'");
    }
};
