@php
$model = $model ?? null;
$title = $title ?? ($model ? $model->getMetaTitle() : setting('site_name', 'Shop VO'));
$description = $description ?? ($model ? $model->getMetaDescription() : setting('site_description', ''));
$keywords = $keywords ?? ($model ? $model->getMetaKeywords() : '');
$ogTitle = $ogTitle ?? ($model ? $model->getOgTitle() : $title);
$ogDescription = $ogDescription ?? ($model ? $model->getOgDescription() : $description);
$ogImage = $ogImage ?? ($model ? $model->getOgImage() : null);
$canonical = $canonical ?? ($model && method_exists($model, 'getCanonicalUrl') ? $model->getCanonicalUrl() : url()->current());
$schema = $schema ?? ($model ? $model->getSchemaMarkup() : null);
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
<meta property="og:type" content="{{ $ogType ?? ($model && get_class($model) === 'App\Product' ? 'product' : 'website') }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
@if($ogImage)
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
@endif
<meta property="og:site_name" content="{{ setting('site_name', 'Shop VO') }}">

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
<meta name="author" content="{{ setting('site_name', 'Shop VO') }}">
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
