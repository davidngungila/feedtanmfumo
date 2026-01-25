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
        Schema::create('loan_sms_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('member_id')->nullable(); // Member ID/Number
            $table->string('loan_number'); // Loan ID/Number
            $table->string('customer_name'); // Customer Name
            $table->string('phone'); // Phone number
            $table->decimal('outstanding_amount', 15, 2)->default(0); // Outstanding Amount
            $table->decimal('monthly_repayment', 15, 2)->default(0); // Monthly Repayment Amount
            $table->enum('repayment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending'); // Monthly Repayment Status
            $table->date('due_date'); // Due Date
            $table->integer('days_overdue')->default(0); // Days Overdue
            $table->string('sms_template')->nullable(); // SMS Template name
            $table->text('sms_message'); // SMS Message
            $table->enum('send_status', ['pending', 'sent', 'failed', 'cancelled'])->default('pending'); // Send Status
            $table->timestamp('send_date')->nullable(); // Send Date
            $table->text('error_message')->nullable(); // Error message if failed
            $table->foreignId('sent_by')->nullable()->constrained('users')->onDelete('set null'); // Who sent it
            $table->timestamps();
            
            // Indexes
            $table->index('loan_id');
            $table->index('user_id');
            $table->index('send_status');
            $table->index('due_date');
            $table->index('send_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_sms_reminders');
    }
};
