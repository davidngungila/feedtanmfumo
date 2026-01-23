<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     */
    public static function set(string $key, $value, string $group = 'system', string $type = 'text'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'group' => $group,
                'type' => $type,
            ]
        );
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group)
    {
        return self::where('group', $group)->get()->keyBy('key');
    }

    /**
     * Get setting value by key with fallback to env
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if ($setting && $setting->value) {
            // Handle boolean type
            if ($setting->type === 'boolean') {
                return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            }
            // Handle number type
            if ($setting->type === 'number' && is_numeric($setting->value)) {
                return (float)$setting->value;
            }
            return $setting->value;
        }
        
        // Fallback to env if not found
        $envKey = strtoupper(str_replace('.', '_', $key));
        return env($envKey, $default);
    }
}
