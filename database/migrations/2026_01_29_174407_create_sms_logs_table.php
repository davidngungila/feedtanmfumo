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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->nullable()->unique();
            $table->string('reference')->nullable();
            $table->string('from')->nullable();
            $table->string('to');
            $table->text('message')->nullable();
            $table->string('channel')->nullable();
            $table->integer('sms_count')->default(1);
            $table->string('status_group_id')->nullable();
            $table->string('status_group_name')->nullable();
            $table->integer('status_id')->nullable();
            $table->string('status_name')->nullable();
            $table->text('status_description')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('done_at')->nullable();
            $table->json('delivery')->nullable();
            $table->json('api_response')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('sent_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('template_id')->nullable();
            $table->string('saving_behavior')->nullable();
            $table->boolean('success')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('to');
            $table->index('from');
            $table->index('sent_at');
            $table->index('status_group_name');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
