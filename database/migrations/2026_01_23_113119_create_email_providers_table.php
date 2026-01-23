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
        Schema::create('email_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Provider Name
            $table->string('mailer')->default('smtp'); // Mail Driver (smtp, sendmail)
            $table->string('host'); // SMTP Host
            $table->integer('port')->default(587); // SMTP Port
            $table->string('encryption')->default('tls'); // Encryption (tls, ssl)
            $table->string('username')->nullable(); // SMTP Username
            $table->string('password')->nullable(); // SMTP Password
            $table->string('from_address'); // From Email Address
            $table->string('from_name')->nullable(); // From Name
            $table->text('description')->nullable(); // Description
            $table->boolean('active')->default(true); // Active status
            $table->boolean('is_primary')->default(false); // Set as Primary
            $table->timestamps();
            
            $table->index('active');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_providers');
    }
};
