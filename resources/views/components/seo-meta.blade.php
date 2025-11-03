@php
$model = $model ?? null;

// Priority: 1) Passed variables, 2) Model methods, 3) Model properties, 4) Site settings
if (!isset($title)) {
    if ($model && method_exists($model, 'getMetaTitle')) {
        $title = $model->getMetaTitle();
    } elseif ($model && isset($model->title)) {
        $title = $model->title . ' - ' . setting('site_name', 'VietNhat365');
    } elseif ($model && isset($model->name)) {
        $title = $model->name . ' - ' . setting('site_name', 'VietNhat365');
    } else {
        $title = setting('site_name', 'VietNhat365') . ' - ' . setting('site_description', 'Cửa hàng trực tuyến uy tín, chất lượng cao với giá cả hợp lý.');
    }
}

if (!isset($description)) {
    $description = '';
    
    if ($model && method_exists($model, 'getMetaDescription')) {
        $description = $model->getMetaDescription();
    }
    
    // If still empty, try model properties
    if (empty($description) && $model) {
        if (isset($model->meta_description) && !empty(trim($model->meta_description))) {
            $description = trim($model->meta_description);
        } elseif (isset($model->excerpt) && !empty(trim($model->excerpt))) {
            $description = trim($model->excerpt);
        } elseif (isset($model->content) && !empty(trim($model->content))) {
            $description = Str::limit(strip_tags($model->content), 160);
        } elseif (isset($model->description) && !empty(trim($model->description))) {
            $description = Str::limit(strip_tags($model->description), 160);
        }
    }
    
    // Final fallback to site settings
    if (empty($description)) {
        $description = setting('site_description', 'Cửa hàng trực tuyến uy tín, chất lượng cao với giá cả hợp lý.');
    }
}

if (!isset($keywords)) {
    $keywords = '';
    
    if ($model && method_exists($model, 'getMetaKeywords')) {
        $keywords = $model->getMetaKeywords();
    }
    
    // If still empty, try model properties
    if (empty($keywords) && $model && isset($model->meta_keywords) && !empty(trim($model->meta_keywords))) {
        $keywords = trim($model->meta_keywords);
    }
    
    // Final fallback to site settings
    if (empty($keywords)) {
        $keywords = setting('site_keywords', 'VietNhat365, mua sắm, điện tử');
    }
}

$ogTitle = $ogTitle ?? ($model ? ($model->title ?? $model->name ?? $title) : $title);
$ogDescription = $ogDescription ?? $description;

if (!isset($ogImage)) {
    if ($model && method_exists($model, 'getOgImage')) {
        $ogImage = $model->getOgImage();
    } elseif ($model && isset($model->featured_image) && $model->featured_image) {
        $ogImage = asset('storage/' . $model->featured_image);
    } elseif ($model && method_exists($model, 'primaryImage') && $model->primaryImage) {
        $ogImage = asset('storage/' . $model->primaryImage->image_path);
    } elseif (setting('site_logo')) {
        $ogImage = asset('storage/' . setting('site_logo'));
    }
}

$canonical = $canonical ?? ($model && method_exists($model, 'getCanonicalUrl') ? $model->getCanonicalUrl() : url()->current());
$schema = $schema ?? ($model && method_exists($model, 'getSchemaMarkup') ? $model->getSchemaMarkup() : null);
@endphp

<!-- SEO Meta Tags -->
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
@if($keywords)
<meta name="keywords" content="{{ $keywords }}">
@endif
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $canonical }}">

<!-- Open Graph / Facebook -->
@php
    $ogTypeDefault = 'website';
    if ($model) {
        $modelClass = get_class($model);
        if (Str::contains($modelClass, 'Product')) {
            $ogTypeDefault = 'product';
        } elseif (Str::contains($modelClass, 'Post') || Str::contains($modelClass, 'Blog')) {
            $ogTypeDefault = 'article';
        }
    }
@endphp
<meta property="og:type" content="{{ $ogType ?? $ogTypeDefault }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
@if($ogImage)
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
@endif
<meta property="og:site_name" content="{{ setting('site_name', 'VietNhat365') }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $canonical }}">
<meta property="twitter:title" content="{{ $ogTitle }}">
<meta property="twitter:description" content="{{ $ogDescription }}">
@if($ogImage)
<meta property="twitter:image" content="{{ $ogImage }}">
@endif

@if($schema)
<!-- Schema.org markup -->
<script type="application/ld+json">
{!! $schema !!}
</script>
@endif

<!-- Additional SEO meta tags -->
<meta name="author" content="{{ $model && isset($model->author) ? $model->author->name : setting('site_name', 'VietNhat365') }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

@if($model && get_class($model) === 'App\Product')
<!-- Product specific meta -->
<meta property="product:price:amount" content="{{ $model->price }}">
<meta property="product:price:currency" content="VND">
<meta property="product:availability" content="{{ $model->stock > 0 ? 'in stock' : 'out of stock' }}">
@if($model->category)
<meta property="product:category" content="{{ $model->category->name }}">
@endif
@endif
