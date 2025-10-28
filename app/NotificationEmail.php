<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationEmail extends Model
{
    protected $fillable = ['email', 'name', 'is_active', 'type', 'note'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getActiveEmails($type = 'order')
    {
        return static::where('is_active', true)
                     ->where('type', $type)
                     ->pluck('email')
                     ->toArray();
    }
}
