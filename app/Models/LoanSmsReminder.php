<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanSmsReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'user_id',
        'member_id',
        'loan_number',
        'customer_name',
        'phone',
        'outstanding_amount',
        'monthly_repayment',
        'repayment_status',
        'due_date',
        'days_overdue',
        'sms_template',
        'sms_message',
        'send_status',
        'send_date',
        'error_message',
        'sent_by',
    ];

    protected $casts = [
        'outstanding_amount' => 'decimal:2',
        'monthly_repayment' => 'decimal:2',
        'due_date' => 'date',
        'days_overdue' => 'integer',
        'send_date' => 'datetime',
    ];

    /**
     * Get the loan associated with this reminder
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get the user (customer) associated with this reminder
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who sent the reminder
     */
    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Scope to get pending reminders
     */
    public function scopePending($query)
    {
        return $query->where('send_status', 'pending');
    }

    /**
     * Scope to get sent reminders
     */
    public function scopeSent($query)
    {
        return $query->where('send_status', 'sent');
    }

    /**
     * Scope to get failed reminders
     */
    public function scopeFailed($query)
    {
        return $query->where('send_status', 'failed');
    }

    /**
     * Scope to get overdue reminders
     */
    public function scopeOverdue($query)
    {
        return $query->where('days_overdue', '>', 0);
    }
}
