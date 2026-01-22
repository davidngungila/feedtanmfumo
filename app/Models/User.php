<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasUlidRouteKey, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ulid',
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'bio',
        'preferences',
        'member_number',
        'status',
        'status_changed_at',
        'status_reason',
        'date_of_birth',
        'gender',
        'national_id',
        'marital_status',
        'alternate_phone',
        'city',
        'region',
        'postal_code',
        'occupation',
        'employer',
        'monthly_income',
        'kyc_status',
        'kyc_expiry_date',
        'group_id',
        'notes',
        'membership_type_id',
        'membership_status',
        'membership_code',
        'number_of_shares',
        'entrance_fee',
        'capital_contribution',
        'capital_outstanding',
        'membership_interest_percentage',
        'bank_name',
        'bank_branch',
        'bank_account_number',
        'short_bibliography',
        'introduced_by',
        'guarantor_name',
        'beneficiaries_info',
        'application_letter_path',
        'payment_slip_path',
        'standing_order_path',
        'nida_picture_path',
        'passport_picture_path',
        'payment_reference_number',
        'statement_preference',
        'is_group_registered',
        'group_name',
        'group_leaders',
        'group_bank_account',
        'group_contacts',
        'wants_ordinary_membership',
        'membership_approved_at',
        'membership_approved_by',
        'membership_application_current_step',
        'membership_application_completed_steps',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'kyc_expiry_date' => 'date',
            'status_changed_at' => 'datetime',
            'preferences' => 'array',
            'entrance_fee' => 'decimal:2',
            'capital_contribution' => 'decimal:2',
            'capital_outstanding' => 'decimal:2',
            'membership_interest_percentage' => 'decimal:2',
            'beneficiaries_info' => 'array',
            'group_leaders' => 'array',
            'group_contacts' => 'array',
            'membership_approved_at' => 'datetime',
            'is_group_registered' => 'boolean',
            'wants_ordinary_membership' => 'boolean',
            'membership_application_completed_steps' => 'array',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get all loans for the user
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get all savings accounts for the user
     */
    public function savingsAccounts()
    {
        return $this->hasMany(SavingsAccount::class);
    }

    /**
     * Get all investments for the user
     */
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    /**
     * Get all social welfare records for the user
     */
    public function socialWelfares()
    {
        return $this->hasMany(SocialWelfare::class);
    }

    /**
     * Get all transactions for the user
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all device tokens for the user
     */
    public function deviceTokens()
    {
        return $this->hasMany(\App\Models\DeviceToken::class);
    }

    /**
     * Get all roles for the user
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roleSlugs): bool
    {
        return $this->roles()->whereIn('slug', $roleSlugs)->exists();
    }

    /**
     * Check if user has all of the given roles
     */
    public function hasAllRoles(array $roleSlugs): bool
    {
        $userRoles = $this->roles()->whereIn('slug', $roleSlugs)->pluck('slug')->toArray();

        return count($userRoles) === count($roleSlugs);
    }

    /**
     * Assign role to user
     */
    public function assignRole(string $roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->first();
        if ($role && ! $this->hasRole($roleSlug)) {
            $this->roles()->attach($role->id);
        }
    }

    /**
     * Remove role from user
     */
    public function removeRole(string $roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->first();
        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Check if user has a specific permission (through roles)
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->isAdmin() || $this->hasRole('admin')) {
            return true; // Admin has all permissions
        }

        return $this->roles()->whereHas('permissions', function ($query) use ($permissionSlug) {
            $query->where('slug', $permissionSlug);
        })->exists();
    }

    /**
     * Get all permissions for the user (through roles)
     */
    public function getPermissions()
    {
        return Permission::whereHas('roles', function ($query) {
            $query->whereIn('id', $this->roles()->pluck('roles.id'));
        })->get();
    }

    /**
     * Get all issues for the user
     */
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    /**
     * Get the membership type
     */
    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class);
    }

    /**
     * Get the user who approved membership
     */
    public function membershipApprovedBy()
    {
        return $this->belongsTo(User::class, 'membership_approved_by');
    }

    /**
     * Check if user has membership access to a service
     */
    public function hasMembershipAccessTo(string $service): bool
    {
        if (! $this->membershipType) {
            return false;
        }

        if ($this->membership_status !== 'approved') {
            return false;
        }

        return $this->membershipType->hasAccessTo($service);
    }

    /**
     * Check if user is a member (not staff/admin)
     */
    public function isMember(): bool
    {
        return $this->membershipType !== null &&
               ! $this->isAdmin() &&
               ! $this->hasAnyRole(['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant']);
    }
}
