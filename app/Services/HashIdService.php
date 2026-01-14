<?php

namespace App\Services;

use Hashids\Hashids;

class HashIdService
{
    private static ?Hashids $hashids = null;

    /**
     * Get Hashids instance
     */
    private static function getHashids(): Hashids
    {
        if (self::$hashids === null) {
            // Use APP_KEY as salt, or a default if not set
            $salt = config('app.key', 'FEEDTAN_DIGITAL_SECRET_KEY');
            // Use minimum length of 8 for shorter URLs
            self::$hashids = new Hashids($salt, 8);
        }

        return self::$hashids;
    }

    /**
     * Encode an ID to a hashid
     */
    public static function encode(int $id): string
    {
        return self::getHashids()->encode($id);
    }

    /**
     * Decode a hashid back to an ID
     */
    public static function decode(string $hashid): ?int
    {
        $decoded = self::getHashids()->decode($hashid);
        return !empty($decoded) ? $decoded[0] : null;
    }

    /**
     * Encode multiple IDs
     */
    public static function encodeArray(array $ids): string
    {
        return self::getHashids()->encode($ids);
    }

    /**
     * Decode multiple IDs
     */
    public static function decodeArray(string $hashid): array
    {
        return self::getHashids()->decode($hashid);
    }
}

