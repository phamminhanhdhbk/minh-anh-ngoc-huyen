<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'route',
        'icon',
        'target',
        'css_class',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the menu that owns this item
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get parent menu item
     */
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get child menu items
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get the URL for this menu item
     */
    public function getUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        if ($this->route) {
            return route($this->route);
        }
        
        return '#';
    }
}
