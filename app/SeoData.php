<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeoData extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'schema_markup',
        'canonical_url',
        'custom_meta'
    ];

    protected $casts = [
        'custom_meta' => 'array'
    ];

    /**
     * Get the owning model.
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Get SEO data for a specific model
     */
    public static function getForModel($model)
    {
        return static::where('model_type', get_class($model))
                    ->where('model_id', $model->id)
                    ->first();
    }

    /**
     * Set SEO data for a specific model
     */
    public static function setForModel($model, $data)
    {
        return static::updateOrCreate(
            [
                'model_type' => get_class($model),
                'model_id' => $model->id
            ],
            $data
        );
    }

    /**
     * Get formatted meta keywords
     */
    public function getFormattedKeywordsAttribute()
    {
        return $this->meta_keywords ? explode(',', $this->meta_keywords) : [];
    }

    /**
     * Generate schema markup for product
     */
    public function generateProductSchema($product)
    {
        if (!$product) return null;

        $schema = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $this->meta_title ?: $product->name,
            'description' => $this->meta_description ?: $product->description,
            'image' => $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) : null,
            'brand' => [
                '@type' => 'Brand',
                'name' => setting('site_name', 'Shop VO')
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->price,
                'priceCurrency' => 'VND',
                'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => setting('site_name', 'Shop VO')
                ]
            ]
        ];

        if ($product->category) {
            $schema['category'] = $product->category->name;
        }

        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
