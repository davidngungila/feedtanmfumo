<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $fillable = [
        'name',
        'url',
        'method',
        'events',
        'headers',
        'secret',
        'is_active',
        'timeout_seconds',
        'retry_attempts',
    ];

    protected $casts = [
        'events' => 'array',
        'headers' => 'array',
        'is_active' => 'boolean',
    ];
}
