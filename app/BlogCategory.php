<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'status', 'order'
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get posts in this category
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'blog_category_id');
    }

    /**
     * Get published posts
     */
    public function publishedPosts()
    {
        return $this->posts()->where('status', 'published')->orderBy('published_at', 'desc');
    }

    /**
     * Scope active categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1)->orderBy('order');
    }
}
