<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    protected $fillable = [
        'category',
        'key',
        'value',
        'description',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, $value, string $category = 'System', ?string $description = null): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'category' => $category,
                'description' => $description,
                'last_updated' => now(),
            ]
        );
    }

    public static function getByCategory(string $category)
    {
        return self::where('category', $category)->get();
    }
}
