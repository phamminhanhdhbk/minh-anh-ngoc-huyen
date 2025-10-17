@extends('layouts.app')

@section('title', setting("site_name", "Ngoc Huyền") . ' - Trang chủ')

@section('content')
<!-- Hero Banner with Gradient -->
<section class="hero-gradient py-5">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6 text-white">
                <h1 class="display-3 fw-bold mb-4 animate-slide-in">
                    Chào mừng đến với <br>
                    <span class="text-warning">{{ setting('site_name', 'Ngoc Huyền') }}</span>
                </h1>
                <p class="lead mb-4 fs-5">
                    {{ setting('site_description', 'Cửa hàng trực tuyến uy tín, chất lượng cao với giá cả hợp lý') }}
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg px-4 py-3 shadow-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
                    </a>
                    <a href="#featured-products" class="btn btn-outline-light btn-lg px-4 py-3">
                        <i class="fas fa-star me-2"></i>Sản phẩm nổi bật
                    </a>
                </div>

                @if(setting('free_shipping_amount'))
                <div class="mt-4">
                    <div class="alert alert-warning d-inline-flex align-items-center shadow">
                        <i class="fas fa-shipping-fast me-2 fs-4"></i>
                        <div>
                            <strong>Miễn phí vận chuyển</strong> cho đơn hàng từ {{ number_format(setting('free_shipping_amount')) }}₫
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image-container">
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=500&fit=crop"
                         alt="Shopping" class="img-fluid rounded-4 shadow-2xl animate-float">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-5 bg-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3 col-6">
                <div class="stat-card p-4 rounded-4 h-100">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-box fa-3x text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ App\Product::where('status', true)->count() }}+</h3>
                    <p class="text-muted mb-0">Sản phẩm</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card p-4 rounded-4 h-100">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-users fa-3x text-success"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ App\User::count() }}+</h3>
                    <p class="text-muted mb-0">Khách hàng</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card p-4 rounded-4 h-100">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-shopping-cart fa-3x text-info"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ App\Order::count() }}+</h3>
                    <p class="text-muted mb-0">Đơn hàng</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card p-4 rounded-4 h-100">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-star fa-3x text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">4.9/5</h3>
                    <p class="text-muted mb-0">Đánh giá</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="categories-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6 mb-3">Danh mục sản phẩm</h2>
            <p class="text-muted fs-5">Khám phá các danh mục sản phẩm đa dạng của chúng tôi</p>
        </div>
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-lg-2 col-md-4 col-6">
                <a href="{{ route('products.index', ['category' => $category->id]) }}"
                   class="text-decoration-none">
                    <div class="category-card-modern text-center p-4 h-100 bg-white rounded-4 shadow-sm">
                        <div class="category-icon mb-3">
                            <i class="fas fa-mobile-alt fa-3x gradient-icon"></i>
                        </div>
                        <h6 class="fw-bold mb-2">{{ $category->name }}</h6>
                        <small class="text-muted">{{ $category->products_count }} sản phẩm</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section id="featured-products" class="featured-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6 mb-3">Sản phẩm nổi bật</h2>
            <p class="text-muted fs-5">Các sản phẩm được đánh giá cao và ưa chuộng nhất</p>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="product-card-modern h-100">
                    <div class="product-image-container">
                        <a href="{{ route('products.show', $product->slug) }}">
                            @if($product->primaryImage)
                                <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                     class="product-image"
                                     alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300"
                                     class="product-image"
                                     alt="{{ $product->name }}">
                            @endif
                        </a>
                        <span class="badge-featured">
                            <i class="fas fa-star"></i> Nổi bật
                        </span>
                    </div>
                    <div class="product-info p-3">
                        <div class="product-category mb-2">
                            <small class="text-muted">{{ $product->category->name }}</small>
                        </div>
                        <h6 class="product-title mb-2">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                                {{ Str::limit($product->name, 50) }}
                            </a>
                        </h6>
                        <div class="product-price mb-3">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="price-sale fw-bold text-danger fs-5">
                                    {{ number_format($product->sale_price) }}₫
                                </span>
                                <span class="price-old text-muted text-decoration-line-through ms-2">
                                    {{ number_format($product->price) }}₫
                                </span>
                            @else
                                <span class="price fw-bold text-primary fs-5">
                                    {{ number_format($product->price) }}₫
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $product->slug) }}"
                           class="btn btn-gradient w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Best Sellers -->
@if($bestSellers->count() > 0)
<section class="best-sellers-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6 mb-3">Sản phẩm bán chạy</h2>
            <p class="text-muted fs-5">Những sản phẩm được khách hàng tin tưởng và lựa chọn nhiều nhất</p>
        </div>
        <div class="row g-4">
            @foreach($bestSellers as $product)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="product-card-simple h-100 bg-white rounded-3 p-3 shadow-sm">
                    <a href="{{ route('products.show', $product->slug) }}">
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                 class="img-fluid rounded mb-3"
                                 alt="{{ $product->name }}">
                        @else
                            <img src="https://via.placeholder.com/200"
                                 class="img-fluid rounded mb-3"
                                 alt="{{ $product->name }}">
                        @endif
                    </a>
                    <h6 class="small mb-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                            {{ Str::limit($product->name, 40) }}
                        </a>
                    </h6>
                    <div class="text-primary fw-bold">
                        {{ number_format($product->sale_price ?? $product->price) }}₫
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@push('styles')
<style>
/* Gradient Hero */
.hero-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    position: relative;
    overflow: hidden;
}

.hero-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);
}

.min-vh-50 {
    min-height: 50vh;
}

/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

.animate-slide-in {
    animation: slideIn 0.8s ease-out;
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
}

.rounded-4 {
    border-radius: 1.5rem !important;
}

/* Stats Cards */
.stat-card {
    background: white;
    transition: all 0.3s ease;
    border: 1px solid #e3e6f0;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Category Cards */
.category-card-modern {
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #e3e6f0;
}

.category-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
}

.gradient-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Product Cards */
.product-card-modern {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    transition: all 0.3s ease;
}

.product-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.product-image-container {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card-modern:hover .product-image {
    transform: scale(1.1);
}

.badge-featured {
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.btn-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

/* Product Simple Card */
.product-card-simple {
    transition: all 0.3s ease;
}

.product-card-simple:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}
</style>
@endpush
@endsection
