<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordPolicy extends Model
{
    protected $fillable = [
        'min_length',
        'require_uppercase',
        'require_lowercase',
        'require_numbers',
        'require_symbols',
        'max_age_days',
        'min_age_days',
        'history_count',
        'lockout_attempts',
        'lockout_duration_minutes',
        'enforce_on_login',
    ];

    protected $casts = [
        'require_uppercase' => 'boolean',
        'require_lowercase' => 'boolean',
        'require_numbers' => 'boolean',
        'require_symbols' => 'boolean',
        'enforce_on_login' => 'boolean',
    ];
}
