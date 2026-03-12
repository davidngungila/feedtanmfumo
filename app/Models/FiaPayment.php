<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiaPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'verification_id',
        'name',
        'phone',
        'email',
        'payment_reference',
        'amount',
        'payment_date',
        'payment_method',
        'description',
        'status',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the verification associated with this payment
     */
    public function verification()
    {
        return $this->belongsTo(FiaVerification::class, 'verification_id');
    }

    /**
     * Get the user who verified this payment
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for verified payments
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope for rejected payments
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute()
    {
        $methods = [
            'mobile_money' => 'Mobile Money',
            'bank_transfer' => 'Bank Transfer',
            'cash' => 'Cash',
            'other' => 'Other'
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get status label with styling
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'verified' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Verified</span>',
            'rejected' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rejected</span>',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
