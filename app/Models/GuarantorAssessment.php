<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUlidRouteKey;
use Illuminate\Support\Str;

class GuarantorAssessment extends Model
{
    use HasUlidRouteKey;

    protected $fillable = [
        'ulid',
        'loan_id',
        'guarantor_id',
        'member_code',
        'full_name',
        'phone',
        'email',
        'relationship',
        'relationship_other',
        'address',
        'occupation',
        'monthly_income',
        'loan_purpose',
        'loan_purpose_other',
        'repayment_history',
        'existing_debts',
        'sufficient_savings',
        'sole_responsibility',
        'recovery_process',
        'voluntary_guarantee',
        'additional_comments',
        'assessment_date',
        'ip_address',
        'user_agent',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'monthly_income' => 'decimal:2',
        'assessment_date' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->ulid) {
                $model->ulid = (string) Str::ulid();
            }
        });
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function guarantor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guarantor_id');
    }
}
