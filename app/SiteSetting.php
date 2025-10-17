<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'sort_order'
    ];

    protected $casts = [
        'sort_order' => 'integer'
    ];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("site_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value
     */
    public static function set($key, $value, $type = 'text', $group = 'general', $label = null, $description = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'label' => $label ?: ucfirst(str_replace('_', ' ', $key)),
                'description' => $description
            ]
        );

        // Clear cache
        Cache::forget("site_setting_{$key}");

        return $setting;
    }

    /**
     * Get all settings grouped
     */
    public static function getAllGrouped()
    {
        return Cache::remember('site_settings_grouped', 3600, function () {
            return static::orderBy('group')->orderBy('sort_order')->get()->groupBy('group');
        });
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllCached()
    {
        return Cache::remember('site_settings_all', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::forget('site_settings_grouped');
        Cache::forget('site_settings_all');
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("site_setting_{$setting->key}");
        }
    }

    /**
     * Get formatted value based on type
     */
    public function getFormattedValueAttribute()
    {
        switch ($this->type) {
            case 'boolean':
                return $this->value ? 'Có' : 'Không';
            case 'image':
                return $this->value ? asset('storage/' . $this->value) : null;
            default:
                return $this->value;
        }
    }

    /**
     * Scope for specific group
     */
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope ordered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
