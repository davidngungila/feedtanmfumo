<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanStatement extends Model
{
    protected $fillable = [
        'user_id',
        'member_id',
        'name',
        'month',
        'year',
        'opening_balance',
        'principal_paid',
        'interest_paid',
        'penalty_paid',
        'total_paid',
        'closing_balance',
        'statement_pdf',
        'notes',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'principal_paid' => 'decimal:2',
        'interest_paid' => 'decimal:2',
        'penalty_paid' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'closing_balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMonthNameAttribute()
    {
        return date("F", mktime(0, 0, 0, $this->month, 10));
    }
}
