<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSeoData;

class Post extends Model
{
    use HasSeoData;

    protected $fillable = [
        'blog_category_id', 'user_id', 'title', 'slug', 'excerpt', 'content', 
        'featured_image', 'status', 'featured', 'views', 'published_at'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'views' => 'integer',
        'published_at' => 'datetime'
    ];

    protected $dates = ['published_at'];

    /**
     * Get the category of the post
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Get the author of the post
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get tags for the post
     */
    public function tags()
    {
        return $this->belongsToMany(PostTag::class, 'post_tag', 'post_id', 'post_tag_id');
    }

    /**
     * Get comments for the post
     */
    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    /**
     * Get approved comments
     */
    public function approvedComments()
    {
        return $this->comments()->where('status', 'approved')->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    /**
     * Scope published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope featured posts
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', 1);
    }

    /**
     * Increment views
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get the route key name
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
