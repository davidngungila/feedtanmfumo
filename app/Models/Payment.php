<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'customer_name',
        'customer_email',
        'phone_number',
        'payment_type',
        'amount',
        'fee',
        'total_amount',
        'currency',
        'status',
        'payment_data',
        'receipt_data',
        'receipt_sent_at',
        'webhook_processed_at'
    ];

    protected $casts = [
        'payment_data' => 'array',
        'receipt_data' => 'array',
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'receipt_sent_at' => 'datetime',
        'webhook_processed_at' => 'datetime'
    ];

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'TSh ' . number_format($this->amount, 0);
    }

    /**
     * Get formatted fee
     */
    public function getFormattedFeeAttribute()
    {
        return 'TSh ' . number_format($this->fee, 0);
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute()
    {
        return 'TSh ' . number_format($this->total_amount, 0);
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful()
    {
        return in_array($this->status, ['completed', 'success', 'paid']);
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return in_array($this->status, ['pending', 'processing', 'initiated']);
    }

    /**
     * Check if payment failed
     */
    public function isFailed()
    {
        return in_array($this->status, ['failed', 'cancelled', 'rejected']);
    }
}
