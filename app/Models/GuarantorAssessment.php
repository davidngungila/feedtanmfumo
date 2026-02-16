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
        'borrower_name',
        'relationship',
        'relationship_other',
        'loan_purpose',
        'loan_purpose_other',
        'reviewed_history',
        'other_debts',
        'sufficient_savings',
        'financial_obligation_impact',
        'other_guarantees',
        'solely_responsible_understanding',
        'recovery_mechanism_understanding',
        'borrower_backup_plan',
        'guarantor_backup_plan',
        'final_declaration',
        'additional_comments',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'final_declaration' => 'boolean',
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
