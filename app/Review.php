<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'comment',
        'images',
        'verified_purchase',
        'approved',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'approved' => 'boolean',
        'verified_purchase' => 'boolean',
        'approved_at' => 'datetime',
        'images' => 'array'
    ];

    /**
     * Get the user that wrote the review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product being reviewed
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who approved the review
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Users who marked this review as helpful
     */
    public function helpfulUsers()
    {
        return $this->belongsToMany(User::class, 'review_helpful', 'review_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    /**
     * Scope for pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('approved', false);
    }

    /**
     * Scope for verified purchase reviews
     */
    public function scopeVerified($query)
    {
        return $query->where('verified_purchase', true);
    }

    /**
     * Get review images URLs
     */
    public function getImageUrlsAttribute()
    {
        if (!$this->images) return [];

        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }

    /**
     * Get helpful count attribute
     */
    public function getHelpfulCountAttribute()
    {
        return $this->helpfulUsers()->count();
    }

    /**
     * Get stars as HTML
     */
    public function getStarsHtmlAttribute()
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $html .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $html .= '<i class="far fa-star text-muted"></i>';
            }
        }
        return $html;
    }

    /**
     * Approve the review
     */
    public function approve($approver = null)
    {
        $this->update([
            'approved' => true,
            'approved_at' => now(),
            'approved_by' => $approver ? $approver->id : auth()->id()
        ]);
    }

    /**
     * Reject the review
     */
    public function reject()
    {
        $this->update([
            'approved' => false,
            'approved_at' => null,
            'approved_by' => null
        ]);
    }

    /**
     * Check if user has purchased this product
     */
    public static function hasUserPurchasedProduct($userId, $productId)
    {
        return \App\Order::where('user_id', $userId)
            ->whereHas('orderItems', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', 'delivered')
            ->exists();
    }

    /**
     * Get average rating for a product
     */
    public static function getAverageRating($productId)
    {
        return static::where('product_id', $productId)
            ->approved()
            ->avg('rating') ?: 0;
    }

    /**
     * Get rating distribution for a product
     */
    public static function getRatingDistribution($productId)
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = static::where('product_id', $productId)
                ->approved()
                ->where('rating', $i)
                ->count();
        }
        return $distribution;
    }
}
