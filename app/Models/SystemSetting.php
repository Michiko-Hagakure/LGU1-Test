<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $connection = 'auth_db';
    protected $table = 'system_settings';

    protected $fillable = [
        'category',
        'key',
        'value',
        'type',
        'description',
        'group',
        'is_public',
        'announcement_image',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get setting value with type casting
     */
    public function getTypedValue()
    {
        return match($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'array', 'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Get a setting value by key with caching
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->getTypedValue() : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'string', string $category = 'general'): void
    {
        $valueToStore = is_array($value) ? json_encode($value) : $value;

        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $valueToStore,
                'type' => $type,
                'category' => $category,
            ]
        );

        Cache::forget("setting.{$key}");
    }

    /**
     * Get all settings by category
     */
    public static function getByCategory(string $category): array
    {
        return Cache::remember("settings.category.{$category}", 3600, function () use ($category) {
            return self::where('category', $category)
                ->get()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => $setting->getTypedValue()];
                })
                ->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}
