<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'view_path',
        'description',
        'thumbnail',
        'author',
        'version',
        'settings',
        'is_active',
        'is_default',
        'order'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean'
    ];

    /**
     * Get the active theme
     */
    public static function getActiveTheme()
    {
        return self::where('is_active', true)->first()
            ?? self::where('is_default', true)->first()
            ?? self::first();
    }

    /**
     * Activate this theme
     */
    public function activate()
    {
        // Deactivate all other themes
        self::where('id', '!=', $this->id)->update(['is_active' => false]);

        // Activate this theme
        $this->update(['is_active' => true]);
    }

    /**
     * Get theme setting value
     */
    public function getSetting($key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Update theme setting
     */
    public function updateSetting($key, $value)
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->update(['settings' => $settings]);
    }
}
