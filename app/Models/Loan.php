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
        'guarantor_id',
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
        'loan_type',
        'collateral_description',
        'collateral_value',
        'guarantor_name',
        'guarantor_phone',
        'guarantor_email',
        'guarantor_address',
        'business_plan',
        'repayment_source',
        'additional_notes',
        'application_document',
        'supporting_documents',
        'id_document',
        'proof_of_income',
        'collateral_document',
        'guarantor_document',
        'agreement_path',
        'schedule_path',
    ];

    protected $casts = [
        'principal_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'collateral_value' => 'decimal:2',
        'application_date' => 'date',
        'approval_date' => 'date',
        'disbursement_date' => 'date',
        'maturity_date' => 'date',
        'supporting_documents' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function guarantor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guarantor_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'related_id')->where('related_type', 'loan');
    }

    public function guarantorAssessments(): HasMany
    {
        return $this->hasMany(GuarantorAssessment::class);
    }
}
