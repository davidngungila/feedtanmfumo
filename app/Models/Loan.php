<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUlidRouteKey;

class Loan extends Model
{
    use HasUlidRouteKey;

    protected $fillable = [
        'ulid',
        'user_id',
        'loan_number',
        'principal_amount',
        'interest_rate',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'term_months',
        'application_date',
        'approval_date',
        'disbursement_date',
        'maturity_date',
        'status',
        'payment_frequency',
        'purpose',
        'rejection_reason',
        'approved_by',
    ];

    protected $casts = [
        'principal_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'application_date' => 'date',
        'approval_date' => 'date',
        'disbursement_date' => 'date',
        'maturity_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'related_id')->where('related_type', 'loan');
    }
}
