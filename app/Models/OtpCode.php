<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class OtpCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'type',
        'expires_at',
        'used',
        'used_at',
        'ip_address',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Get the user that owns the OTP code
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if OTP is valid (not used and not expired)
     */
    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    /**
     * Mark OTP as used
     */
    public function markAsUsed(): void
    {
        $this->update([
            'used' => true,
            'used_at' => now(),
        ]);
    }

    /**
     * Generate a random 6-digit OTP code
     */
    public static function generateCode(): string
    {
        return str_pad((string) rand(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new OTP code for a user
     */
    public static function createForUser(User $user, string $type = 'login', int $expiryMinutes = 10): self
    {
        // Invalidate any existing unused OTPs for this user and type
        self::where('user_id', $user->id)
            ->where('type', $type)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->update(['used' => true]);

        return self::create([
            'user_id' => $user->id,
            'code' => self::generateCode(),
            'type' => $type,
            'expires_at' => now()->addMinutes($expiryMinutes),
            'ip_address' => request()->ip(),
        ]);
    }
}
