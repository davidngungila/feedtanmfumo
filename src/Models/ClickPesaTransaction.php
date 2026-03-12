<?php

namespace FeedTan\ClickPesa\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClickPesaTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'reference',
        'amount',
        'currency',
        'payment_method',
        'status',
        'phone_number',
        'card_last4',
        'provider',
        'description',
        'metadata',
        'callback_url',
        'return_url',
        'expires_at',
        'completed_at',
        'failure_reason',
        'response_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'response_data' => 'array',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the human-readable status.
     */
    public function getFormattedStatusAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded',
            default => ucfirst($this->status),
        };
    }

    /**
     * Check if transaction is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if transaction is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
}
