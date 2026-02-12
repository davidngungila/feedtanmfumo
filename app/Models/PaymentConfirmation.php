<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentConfirmation extends Model
{
    protected $fillable = [
        'user_id',
        'member_id',
        'member_name',
        'member_type',
        'amount_to_pay',
        'deposit_balance',
        'swf_contribution',
        're_deposit',
        'fia_investment',
        'fia_type',
        'capital_contribution',
        'loan_repayment',
        'member_email',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount_to_pay' => 'decimal:2',
            'deposit_balance' => 'decimal:2',
            'swf_contribution' => 'decimal:2',
            're_deposit' => 'decimal:2',
            'fia_investment' => 'decimal:2',
            'capital_contribution' => 'decimal:2',
            'loan_repayment' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalDistributionAttribute(): float
    {
        return $this->swf_contribution +
               $this->re_deposit +
               $this->fia_investment +
               $this->capital_contribution +
               $this->loan_repayment;
    }
}
