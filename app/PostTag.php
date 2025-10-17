<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Get posts with this tag
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag', 'post_tag_id', 'post_id');
    }

    /**
     * Get the route key name
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
