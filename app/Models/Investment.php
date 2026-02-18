<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUlidRouteKey;

class Investment extends Model
{
    use HasUlidRouteKey;

    protected $fillable = [
        'ulid',
        'user_id',
        'investment_number',
        'plan_type',
        'principal_amount',
        'interest_rate',
        'unit_price',
        'expected_return',
        'profit_share',
        'start_date',
        'maturity_date',
        'disbursement_date',
        'payment_receipt',
        'status',
        'maturity_alert_sent',
        'notes',
    ];

    protected $casts = [
        'principal_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'expected_return' => 'decimal:2',
        'profit_share' => 'decimal:2',
        'start_date' => 'date',
        'maturity_date' => 'date',
        'disbursement_date' => 'date',
        'maturity_alert_sent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'related_id')->where('related_type', 'investment');
    }

    public function getPlanTypeNameAttribute(): string
    {
        return match($this->plan_type) {
            '4_year' => '8.6% Four-years FIA (Priced at TZS 110/100)',
            '6_year' => '10% Six-years FIA (Priced at TZS 120/100)',
            default => ucfirst($this->plan_type),
        };
    }
}
