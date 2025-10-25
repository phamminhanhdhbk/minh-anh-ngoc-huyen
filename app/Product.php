<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasSeoData;

class Product extends Model
{
    use HasSeoData, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'sale_price',
        'stock', 'sku', 'image', 'gallery', 'status', 'featured', 'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'status' => 'boolean',
        'featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->ordered();
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->primary();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->approved();
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists', 'product_id', 'user_id')
                    ->withTimestamps();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getSalePriceAttribute($value)
    {
        return $value ? $value : $this->price;
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->sale_price && $this->sale_price < $this->price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    /**
     * Get primary image URL or fallback to old image field
     */
    public function getPrimaryImageUrlAttribute()
    {
        if ($this->primaryImage) {
            return $this->primaryImage->image_url;
        }

        // Fallback to old image field
        if ($this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }

        return asset('images/no-image.png');
    }

    // SEO method overrides
    protected function getDefaultMetaTitle()
    {
        return $this->name . ' - ' . setting('site_name', 'Shop VO');
    }

    protected function getDefaultMetaDescription()
    {
        $description = strip_tags($this->description);
        return substr($description, 0, 155) . (strlen($description) > 155 ? '...' : '');
    }

    protected function getDefaultMetaKeywords()
    {
        $keywords = [$this->name];
        if ($this->category) {
            $keywords[] = $this->category->name;
        }
        $keywords[] = setting('site_name', 'Shop VO');
        return implode(', ', $keywords);
    }

    // Review methods
    public function getAverageRatingAttribute()
    {
        return Review::getAverageRating($this->id);
    }

    public function getReviewsCountAttribute()
    {
        return $this->approvedReviews()->count();
    }

    public function getRatingDistributionAttribute()
    {
        return Review::getRatingDistribution($this->id);
    }

    public function getRatingDistribution()
    {
        return Review::getRatingDistribution($this->id);
    }

    public function getStarsHtmlAttribute()
    {
        $rating = $this->average_rating;
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $html .= '<i class="fas fa-star text-warning"></i>';
            } elseif ($i - 0.5 <= $rating) {
                $html .= '<i class="fas fa-star-half-alt text-warning"></i>';
            } else {
                $html .= '<i class="far fa-star text-muted"></i>';
            }
        }
        return $html;
    }

    protected function getDefaultOgImage()
    {
        return $this->primary_image_url;
    }

    protected function generateDefaultSchema()
    {
        $seo = $this->getSeoData();
        return $seo->generateProductSchema($this);
    }
}
