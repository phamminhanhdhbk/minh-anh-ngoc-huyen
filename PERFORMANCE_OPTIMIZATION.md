# 🚀 Performance Optimization Guide

## 📦 Cài đặt Package cần thiết

### 1. Image Optimization (Intervention Image)
```bash
composer require intervention/image
```

### 2. Redis Cache (Optional - recommended for production)
```bash
composer require predis/predis
```

## 🖼️ Tối ưu hình ảnh

### Optimize tất cả hình ảnh trong thư mục
```bash
php artisan images:optimize products
```

### Sử dụng ImageOptimizationService trong code
```php
use App\Services\ImageOptimizationService;

$imageService = new ImageOptimizationService();

// Optimize một ảnh
$imageService->optimizeImage('products/image.jpg', 1200, 80);

// Tạo responsive images (thumbnail, medium, large)
$images = $imageService->createResponsiveImages('products/image.jpg');

// Convert sang WebP
$webpPath = $imageService->convertToWebP('products/image.jpg');
```

## 💾 Cache Laravel

### Clear all caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Enable caching (Production)
```bash
php artisan config:cache
php artisan view:cache
# php artisan route:cache # Chỉ dùng nếu không có route closure
```

### Sử dụng Query Cache Helper
```php
use App\Helpers\QueryCacheHelper;

// Cache products
$products = QueryCacheHelper::getCachedProducts(['featured' => true], 12);

// Cache categories
$categories = QueryCacheHelper::getCachedCategories();

// Cache theme
$theme = QueryCacheHelper::getCachedActiveTheme();

// Clear cache
QueryCacheHelper::clearProductCache();
QueryCacheHelper::clearCategoryCache();
```

## ⚡ Browser Caching

File `.htaccess` đã được cấu hình với:
- ✅ GZIP Compression
- ✅ Browser Caching (images: 1 năm, CSS/JS: 1 tháng)
- ✅ Cache-Control Headers

## 🗄️ Database Optimization

### 1. Eager Loading (tránh N+1 query problem)
```php
// ❌ Bad
$products = Product::all();
foreach ($products as $product) {
    echo $product->category->name; // N+1 queries
}

// ✅ Good
$products = Product::with('category')->get();
foreach ($products as $product) {
    echo $product->category->name; // 1 query
}
```

### 2. Select only needed columns
```php
// ❌ Bad
$products = Product::all();

// ✅ Good
$products = Product::select(['id', 'name', 'price', 'slug'])->get();
```

### 3. Use pagination
```php
$products = Product::paginate(12); // Thay vì ->get()
```

## 📊 Monitoring Performance

### 1. Enable Laravel Debugbar (Development only)
```bash
composer require barryvdh/laravel-debugbar --dev
```

### 2. Check query count
```php
\DB::enableQueryLog();
// Your code here
dd(\DB::getQueryLog());
```

## 🔧 Production Checklist

- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `php artisan images:optimize products`
- [ ] Enable Redis cache (edit `.env`: `CACHE_DRIVER=redis`)
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Enable OPcache trong PHP
- [ ] Cấu hình CDN cho static assets
- [ ] Enable HTTP/2 trên server
- [ ] Sử dụng Laravel Horizon cho queue (nếu cần)

## 🎯 Image Best Practices

1. **Format tối ưu:**
   - JPEG: Photos, complex images (quality 80-85%)
   - PNG: Logos, icons với nền trong suốt
   - WebP: Modern format, nhỏ hơn 25-35% (nếu browser support)

2. **Kích thước đề xuất:**
   - Thumbnail: 300x300px
   - Medium: 800x800px
   - Large: 1200x1200px
   - Full: Max 1920px width

3. **Lazy Loading:**
   ```html
   <img src="image.jpg" loading="lazy" alt="Description">
   ```

## 🌐 CDN Integration (Optional)

### 1. Cấu hình AWS S3 hoặc DigitalOcean Spaces
```bash
composer require league/flysystem-aws-s3-v3
```

### 2. Update `.env`
```env
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
AWS_URL=https://your-cdn-url.com
```

### 3. Upload images to S3
```php
Storage::disk('s3')->put('products/image.jpg', file_get_contents($file));
```

## 📈 Expected Performance Improvements

Sau khi áp dụng:
- ⚡ Page load time giảm: **40-60%**
- 🖼️ Image size giảm: **50-70%**
- 💾 Database queries giảm: **30-50%**
- 🚀 Server response time giảm: **30-40%**

## 🔍 Testing Tools

- Google PageSpeed Insights: https://pagespeed.web.dev/
- GTmetrix: https://gtmetrix.com/
- WebPageTest: https://www.webpagetest.org/
- Chrome DevTools Network Tab

## 📞 Support

Nếu có vấn đề, hãy check:
1. PHP error logs: `storage/logs/laravel.log`
2. Clear cache: `php artisan cache:clear`
3. Restart web server
4. Check file permissions: `storage/` và `bootstrap/cache/` phải writable
