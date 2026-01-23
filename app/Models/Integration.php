<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $fillable = [
        'name',
        'type',
        'provider',
        'credentials',
        'settings',
        'is_active',
        'is_test_mode',
        'description',
    ];

    protected $casts = [
        'credentials' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
    ];
}
