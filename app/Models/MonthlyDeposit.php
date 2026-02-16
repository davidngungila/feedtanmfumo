<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyDeposit extends Model
{
    protected $fillable = [
        'user_id',
        'member_id',
        'name',
        'month',
        'year',
        'savings',
        'shares',
        'welfare',
        'loan_principal',
        'loan_interest',
        'fine_penalty',
        'total',
        'notes',
    ];

    protected $casts = [
        'savings' => 'decimal:2',
        'shares' => 'decimal:2',
        'welfare' => 'decimal:2',
        'loan_principal' => 'decimal:2',
        'loan_interest' => 'decimal:2',
        'fine_penalty' => 'decimal:2',
        'total' => 'decimal:2',
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
