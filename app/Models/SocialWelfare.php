<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUlidRouteKey;

class SocialWelfare extends Model
{
    use HasUlidRouteKey;

    protected $fillable = [
        'ulid',
        'user_id',
        'welfare_number',
        'type',
        'benefit_type',
        'amount',
        'transaction_date',
        'status',
        'description',
        'eligibility_notes',
        'rejection_reason',
        'approved_by',
        'approval_date',
        'disbursement_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'approval_date' => 'date',
        'disbursement_date' => 'date',
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
        return $this->hasMany(Transaction::class, 'related_id')->where('related_type', 'social_welfare');
    }

    public function getBenefitTypeNameAttribute(): ?string
    {
        return match($this->benefit_type) {
            'medical' => 'Medical Support',
            'funeral' => 'Funeral Support',
            'educational' => 'Educational Support',
            'other' => 'Other Support',
            default => null,
        };
    }
}
