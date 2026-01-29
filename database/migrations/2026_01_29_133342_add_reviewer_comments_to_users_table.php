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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('editing_requested')->default(false)->after('membership_application_completed_steps');
            $table->text('reviewer_comments')->nullable()->after('editing_requested');
            $table->timestamp('editing_requested_at')->nullable()->after('reviewer_comments');
            $table->foreignId('editing_requested_by')->nullable()->after('editing_requested_at')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['editing_requested_by']);
            $table->dropColumn(['editing_requested', 'reviewer_comments', 'editing_requested_at', 'editing_requested_by']);
        });
    }
};
