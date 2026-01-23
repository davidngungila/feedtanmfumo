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
        Schema::create('system_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version'); // e.g., 1.0.0
            $table->string('codename')->nullable();
            $table->date('release_date');
            $table->enum('type', ['major', 'minor', 'patch', 'hotfix'])->default('patch');
            $table->text('changelog')->nullable();
            $table->json('features')->nullable();
            $table->json('bug_fixes')->nullable();
            $table->json('breaking_changes')->nullable();
            $table->boolean('is_current')->default(false);
            $table->boolean('is_available')->default(true);
            $table->string('download_url')->nullable();
            $table->timestamps();
            
            $table->unique('version');
            $table->index('is_current');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_versions');
    }
};
