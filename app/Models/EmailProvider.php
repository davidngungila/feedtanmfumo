<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mailer',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'from_address',
        'from_name',
        'description',
        'active',
        'is_primary',
    ];

    protected $casts = [
        'port' => 'integer',
        'active' => 'boolean',
        'is_primary' => 'boolean',
    ];

    /**
     * Get the primary email provider
     */
    public static function getPrimary(): ?self
    {
        return self::where('active', true)
            ->where('is_primary', true)
            ->first();
    }

    /**
     * Get active providers
     */
    public static function getActive(): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('active', true)
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Set this provider as primary
     */
    public function setAsPrimary(): void
    {
        // Unset other primary providers
        self::where('id', '!=', $this->id)->update(['is_primary' => false]);
        
        // Set this as primary
        $this->update(['is_primary' => true, 'active' => true]);
    }
}
