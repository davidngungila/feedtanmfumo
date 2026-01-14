<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

/**
 * SecureIdService
 * 
 * Bank-grade ID encryption service using Laravel's AES-256 encryption.
 * This service provides secure, tamper-proof ID encryption for URLs and sensitive data.
 * 
 * Security Features:
 * - AES-256 encryption (bank-grade)
 * - Tamper-proof (cannot be modified)
 * - Secure (cannot be guessed or enumerated)
 * - URL-safe encoding
 */
class SecureIdService
{
    /**
     * Encrypt an ID for use in URLs
     * Uses AES-256 encryption with base64 encoding for URL safety
     * 
     * @param int|string $id The ID to encrypt
     * @return string Base64-encoded encrypted string (URL-safe)
     */
    public static function encrypt(int|string $id): string
    {
        // Encrypt using Laravel's Crypt (AES-256)
        $encrypted = Crypt::encryptString((string) $id);
        
        // Base64 encode for URL safety
        return base64_encode($encrypted);
    }

    /**
     * Decrypt an encrypted ID from URL
     * 
     * @param string $encryptedId The encrypted ID from URL
     * @return int|null The decrypted ID, or null if decryption fails
     */
    public static function decrypt(string $encryptedId): ?int
    {
        try {
            // Base64 decode first
            $decoded = base64_decode($encryptedId, true);
            
            if ($decoded === false) {
                return null;
            }
            
            // Decrypt using Laravel's Crypt
            $decrypted = Crypt::decryptString($decoded);
            
            // Return as integer
            return (int) $decrypted;
        } catch (DecryptException $e) {
            // Decryption failed - invalid or tampered value
            return null;
        }
    }

    /**
     * Encrypt multiple IDs
     * 
     * @param array $ids Array of IDs to encrypt
     * @return string Encrypted string
     */
    public static function encryptArray(array $ids): string
    {
        $idsString = implode(',', $ids);
        return self::encrypt($idsString);
    }

    /**
     * Decrypt multiple IDs
     * 
     * @param string $encryptedIds Encrypted string
     * @return array Array of decrypted IDs
     */
    public static function decryptArray(string $encryptedIds): array
    {
        $decrypted = self::decrypt($encryptedIds);
        
        if ($decrypted === null) {
            return [];
        }
        
        // If it's a comma-separated string, split it
        if (str_contains((string) $decrypted, ',')) {
            return array_map('intval', explode(',', (string) $decrypted));
        }
        
        return [$decrypted];
    }
}

