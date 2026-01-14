<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUlidRouteKey;

class SavingsAccount extends Model
{
    use HasUlidRouteKey;

    protected $fillable = [
        'ulid',
        'user_id',
        'account_number',
        'account_type',
        'balance',
        'interest_rate',
        'minimum_balance',
        'opening_date',
        'maturity_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'minimum_balance' => 'decimal:2',
        'opening_date' => 'date',
        'maturity_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'related_id')->where('related_type', 'savings_account');
    }

    public function getAccountTypeNameAttribute(): string
    {
        return match($this->account_type) {
            'emergency' => 'Emergency Savings',
            'rda' => 'Recurrent Deposit Account',
            'flex' => 'Flex Account',
            'business' => 'Business Savings',
            default => ucfirst($this->account_type),
        };
    }
}
