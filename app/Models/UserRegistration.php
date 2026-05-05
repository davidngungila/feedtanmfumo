<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserRegistration extends Model
{
    protected $fillable = [
        'registration_token',
        'current_step',
        'completed',
        'step_data',
        'email',
        'phone',
        'name',
        'expires_at',
    ];

    protected $casts = [
        'step_data' => 'array',
        'completed' => 'boolean',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            $registration->registration_token = Str::uuid();
            $registration->expires_at = now()->addHours(24);
        });
    }

    public function getStepData($step = null)
    {
        if ($step) {
            return $this->step_data["step_{$step}"] ?? [];
        }
        return $this->step_data ?? [];
    }

    public function setStepData($step, $data)
    {
        $stepData = $this->step_data ?? [];
        $stepData["step_{$step}"] = $data;
        $this->step_data = $stepData;
        $this->save();
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
