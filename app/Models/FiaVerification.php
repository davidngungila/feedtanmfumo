<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiaVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'payment_reference',
        'amount',
        'payment_date',
        'payment_method',
        'description',
        'status',
        'submitted_at',
        'verified_at',
        'verified_by',
        'verification_notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the payment record associated with this verification
     */
    public function payment()
    {
        return $this->hasOne(FiaPayment::class, 'verification_id');
    }

    /**
     * Get the user who verified this payment
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope for pending verifications
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
     * Scope for rejected verifications
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
