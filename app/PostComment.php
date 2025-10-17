<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $fillable = [
        'post_id', 'user_id', 'parent_id', 'name', 'email', 'content', 'status'
    ];

    /**
     * Get the post of the comment
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user of the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get parent comment
     */
    public function parent()
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    /**
     * Get child comments (replies)
     */
    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id')->where('status', 'approved');
    }

    /**
     * Scope approved comments
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
