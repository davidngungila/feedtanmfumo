<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'entrance_fee',
        'capital_contribution',
        'minimum_shares',
        'maximum_shares',
        'membership_interest_percentage',
        'access_permissions',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'access_permissions' => 'array',
        'is_active' => 'boolean',
        'entrance_fee' => 'integer',
        'capital_contribution' => 'integer',
        'minimum_shares' => 'integer',
        'maximum_shares' => 'integer',
        'membership_interest_percentage' => 'decimal:2',
    ];

    /**
     * Get all users with this membership type
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if membership type has access to a service
     */
    public function hasAccessTo(string $service): bool
    {
        if (!$this->access_permissions) {
            return false;
        }
        
        return in_array($service, $this->access_permissions) || 
               in_array('all', $this->access_permissions);
    }
}
