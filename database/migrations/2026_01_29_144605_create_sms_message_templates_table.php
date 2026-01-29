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
        Schema::create('sms_message_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name');
            $table->string('behavior_type')->nullable(); // Inconsistent Saver, Sporadic Saver, Non-Saver, Regular Saver
            $table->text('message_content');
            $table->string('language')->default('sw'); // sw, en
            $table->integer('priority')->default(1); // 1 = highest
            $table->json('variables')->nullable(); // ['name', 'amount', 'organization_name']
            $table->timestamp('last_modified')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_message_templates');
    }
};
