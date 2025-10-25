<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class QueryCacheHelper
{
    /**
     * Cache duration in minutes
     */
    const CACHE_SHORT = 5;      // 5 minutes
    const CACHE_MEDIUM = 30;    // 30 minutes
    const CACHE_LONG = 1440;    // 24 hours
    const CACHE_WEEK = 10080;   // 1 week

    /**
     * Get cached query or execute and cache
     *
     * @param string $key Cache key
     * @param \Closure $callback Query callback
     * @param int $minutes Cache duration in minutes
     * @return mixed
     */
    public static function remember($key, $callback, $minutes = self::CACHE_MEDIUM)
    {
        return Cache::remember($key, now()->addMinutes($minutes), $callback);
    }

    /**
     * Clear cache by key or pattern
     *
     * @param string $keyOrPattern
     * @return bool
     */
    public static function forget($keyOrPattern)
    {
        return Cache::forget($keyOrPattern);
    }

    /**
     * Clear all cache with specific prefix
     *
     * @param string $prefix
     * @return void
     */
    public static function clearByPrefix($prefix)
    {
        // This works with Redis/Memcached, for file cache you need different approach
        Cache::tags([$prefix])->flush();
    }

    /**
     * Get cached products
     *
     * @param array $conditions
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCachedProducts($conditions = [], $limit = 12)
    {
        $cacheKey = 'products_' . md5(serialize($conditions) . $limit);

        return self::remember($cacheKey, function() use ($conditions, $limit) {
            $query = \App\Product::with(['category', 'primaryImage'])
                ->where('status', true);

            foreach ($conditions as $field => $value) {
                $query->where($field, $value);
            }

            return $query->limit($limit)->get();
        }, self::CACHE_MEDIUM);
    }

    /**
     * Get cached categories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCachedCategories()
    {
        return self::remember('categories_active', function() {
            return \App\Category::where('status', true)
                ->withCount('products')
                ->orderBy('id')
                ->get();
        }, self::CACHE_LONG);
    }

    /**
     * Get cached theme
     *
     * @return \App\Theme|null
     */
    public static function getCachedActiveTheme()
    {
        return self::remember('theme_active', function() {
            return \App\Theme::getActiveTheme();
        }, self::CACHE_WEEK);
    }

    /**
     * Get cached settings
     *
     * @return array
     */
    public static function getCachedSettings()
    {
        return self::remember('settings_all', function() {
            return \App\Setting::pluck('value', 'key')->toArray();
        }, self::CACHE_LONG);
    }

    /**
     * Clear product-related caches
     *
     * @return void
     */
    public static function clearProductCache()
    {
        self::forget('products_*');
        self::forget('featured_products');
        self::forget('latest_products');
        self::forget('best_sellers');
    }

    /**
     * Clear category cache
     *
     * @return void
     */
    public static function clearCategoryCache()
    {
        self::forget('categories_active');
    }

    /**
     * Clear theme cache
     *
     * @return void
     */
    public static function clearThemeCache()
    {
        self::forget('theme_active');
    }
}
