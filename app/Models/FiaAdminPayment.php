<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiaAdminPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fia_gawio',
        'fia_iliyokomaa',
        'jumla',
        'malipo_vipande',
        'loan',
        'kiasi_baki',
        'membership_code',
        'status',
    ];

    protected $casts = [
        'fia_gawio' => 'decimal:2',
        'fia_iliyokomaa' => 'decimal:2',
        'jumla' => 'decimal:2',
        'malipo_vipande' => 'decimal:2',
        'loan' => 'decimal:2',
        'kiasi_baki' => 'decimal:2',
    ];

    /**
     * Get the member associated with this payment
     */
    public function member()
    {
        return $this->belongsTo(User::class, 'membership_code', 'membership_code');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for verified payments
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Get formatted amount fields
     */
    public function getFormattedFiaGawioAttribute()
    {
        return number_format($this->fia_gawio, 2);
    }

    public function getFormattedFiaIliyokomaaAttribute()
    {
        return number_format($this->fia_iliyokomaa, 2);
    }

    public function getFormattedJumlaAttribute()
    {
        return number_format($this->jumla, 2);
    }

    public function getFormattedMalipoVipandeAttribute()
    {
        return number_format($this->malipo_vipande, 2);
    }

    public function getFormattedLoanAttribute()
    {
        return number_format($this->loan, 2);
    }

    public function getFormattedKiasiBakiAttribute()
    {
        return number_format($this->kiasi_baki, 2);
    }

    /**
     * Get status label with styling
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'verified' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Verified</span>',
            'rejected' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rejected</span>',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Calculate total amount
     */
    public function getTotalAmountAttribute()
    {
        return ($this->fia_gawio ?? 0) + 
               ($this->fia_iliyokomaa ?? 0) + 
               ($this->malipo_vipande ?? 0) + 
               ($this->loan ?? 0);
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 2);
    }
}
