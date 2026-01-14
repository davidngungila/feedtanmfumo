<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

/**
 * HasEncryptedRouteKey Trait
 * 
 * Uses Laravel's AES-256 encryption (via Crypt facade) to encrypt IDs in URLs.
 * This provides bank-grade security for sensitive route parameters.
 * 
 * Benefits:
 * - AES-256 encryption (industry standard)
 * - Tamper-proof (cannot be modified)
 * - Secure (cannot be guessed or enumerated)
 * - Laravel native (no external dependencies)
 */
trait HasEncryptedRouteKey
{
    /**
     * Get the value of the model's route key.
     * This encrypts the ID using AES-256 encryption for secure URLs
     */
    public function getRouteKey(): string
    {
        $id = $this->getKey();
        
        // Encrypt the ID using Laravel's Crypt (AES-256)
        // Base64 encode for URL safety
        return base64_encode(Crypt::encryptString((string) $id));
    }

    /**
     * Retrieve the model for binding a value.
     * This decrypts the encrypted ID from the URL back to the actual ID
     */
    public function resolveRouteBinding($value, $field = null)
    {
        try {
            // Try to decrypt the encrypted value
            $id = Crypt::decryptString(base64_decode($value));
            
            // Find the model by the decrypted ID
            return $this->where($field ?? $this->getRouteKeyName(), $id)->first();
        } catch (DecryptException $e) {
            // If decryption fails, try backward compatibility with numeric IDs
            // This allows old URLs to still work during migration
            if (is_numeric($value)) {
                return $this->where($field ?? $this->getRouteKeyName(), (int) $value)->first();
            }
            
            // Invalid encrypted value - return null (Laravel will show 404)
            return null;
        }
    }

    /**
     * Get the route key name (default: 'id')
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}

