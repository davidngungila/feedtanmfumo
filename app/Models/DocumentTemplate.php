<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    protected $fillable = [
        'name',
        'type',
        'category',
        'content',
        'variables',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
    ];
}
