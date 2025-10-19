@extends('layouts.app')

@section('title', setting("site_name", "BanDuThu.com") . ' - Trang chủ')

@section('content')
<!-- Top Header Bar -->
<div class="top-header-bar bg-danger text-white py-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <small>
                    <i class="fas fa-shipping-fast me-2"></i>Giao siêu nhanh 2h Hà Nội - Giao nhanh toàn quốc
                </small>
            </div>
            <div class="col-md-6 text-end">
                <small>
                    <i class="fas fa-phone me-2"></i>Tư vấn: {{ setting('contact_phone', '1900-000-000') }}
                    <span class="mx-2">|</span>
                    <i class="fas fa-map-marker-alt me-2"></i>Hệ thống cửa hàng
                </small>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
            <!-- Categories Menu -->
            <div class="categories-sidebar shadow-sm mb-4">
                <div class="categories-header bg-danger text-white p-3">
                    <h5 class="mb-0">
                        <i class="fas fa-bars me-2"></i>DANH MUC SẢN PHẨM
                    </h5>
                </div>
                <div class="categories-list">
                    @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}"
                       class="category-item d-flex align-items-center p-3 border-bottom text-decoration-none">
                        <i class="fas fa-angle-right me-2 text-danger"></i>
                        <span class="flex-grow-1 text-dark">{{ $category->name }}</span>
                        <span class="badge bg-light text-dark">{{ $category->products_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Hot Deals -->
            <div class="hot-deals-sidebar bg-white shadow-sm mb-4 rounded">
                <div class="p-3 bg-warning text-dark">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-fire me-2"></i>DEALS HOT
                    </h6>
                </div>
                <div class="p-3">
                    @foreach($bestSellers->take(3) as $product)
                    <div class="deal-item mb-3 pb-3 border-bottom">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                            <div class="row align-items-center">
                                <div class="col-4">
                                    @if($product->primaryImage)
                                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                             class="img-fluid rounded"
                                             alt="{{ $product->name }}">
                                    @else
                                        <img src="https://via.placeholder.com/100"
                                             class="img-fluid rounded"
                                             alt="{{ $product->name }}">
                                    @endif
                                </div>
                                <div class="col-8">
                                    <h6 class="small mb-1 text-dark">{{ Str::limit($product->name, 40) }}</h6>
                                    <div class="text-danger fw-bold">
                                        {{ number_format($product->sale_price ?? $product->price) }}₫
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Services Box -->
            <div class="services-box bg-white shadow-sm rounded p-3">
                <div class="service-item d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="service-icon me-3">
                        <i class="fas fa-truck fa-2x text-danger"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 small fw-bold">Giao hàng nhanh</h6>
                        <small class="text-muted">Toàn quốc</small>
                    </div>
                </div>
                <div class="service-item d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="service-icon me-3">
                        <i class="fas fa-undo fa-2x text-danger"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 small fw-bold">Đổi trả dễ dàng</h6>
                        <small class="text-muted">Trong 7 ngày</small>
                    </div>
                </div>
                <div class="service-item d-flex align-items-center">
                    <div class="service-icon me-3">
                        <i class="fas fa-shield-alt fa-2x text-danger"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 small fw-bold">Thanh toán</h6>
                        <small class="text-muted">An toàn bảo mật</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
            <!-- Main Banners -->
            <div class="row mb-4">
                <div class="col-lg-8 mb-3">
                    <!-- Main Slider -->
                    <div id="mainSlider" class="carousel slide shadow-sm rounded overflow-hidden" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="2"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=800&h=400&fit=crop"
                                     class="d-block w-100" alt="Banner 1">
                                <div class="carousel-caption">
                                    <h5 class="fw-bold">Giày mới cực xinh</h5>
                                    <p>Lựng linh xuống phố</p>
                                    <a href="{{ route('products.index') }}" class="btn btn-warning btn-sm">Mua ngay</a>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=800&h=400&fit=crop"
                                     class="d-block w-100" alt="Banner 2">
                                <div class="carousel-caption">
                                    <h5 class="fw-bold">Flash Sale</h5>
                                    <p>Mua 2 tặng 1</p>
                                    <a href="{{ route('products.index') }}" class="btn btn-danger btn-sm">Xem ngay</a>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=400&fit=crop"
                                     class="d-block w-100" alt="Banner 3">
                                <div class="carousel-caption">
                                    <h5 class="fw-bold">Đồng hồ Movado</h5>
                                    <p>Chính hãng</p>
                                    <a href="{{ route('products.index') }}" class="btn btn-warning btn-sm">Khám phá</a>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <img src="https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=400&h=190&fit=crop"
                             class="img-fluid rounded shadow-sm" alt="Sub Banner 1">
                    </div>
                    <div>
                        <img src="https://images.unsplash.com/photo-1515955656352-a1fa3ffcd111?w=400&h=190&fit=crop"
                             class="img-fluid rounded shadow-sm" alt="Sub Banner 2">
                    </div>
                </div>
            </div>

            <!-- Small Banners -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="promo-banner bg-danger text-white rounded shadow-sm p-3 text-center">
                        <h6 class="mb-1">TẶNG BÓNG DB/K3</h6>
                        <small>Mở hộp may mắn</small>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="promo-banner bg-warning text-dark rounded shadow-sm p-3 text-center">
                        <h6 class="mb-1">KÍNH HOT TREND</h6>
                        <small>Giảm hàng loạt sản phẩm</small>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="promo-banner bg-primary text-white rounded shadow-sm p-3 text-center">
                        <h6 class="mb-1">FLASH SALE</h6>
                        <small>Mua 2 tặng 1</small>
                    </div>
                </div>
            </div>

            <!-- Top Best Sellers -->
            <div class="section-header bg-warning text-center py-3 rounded-top mb-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-fire me-2"></i>Top bán chạy nhất tuần
                </h5>
            </div>

            <!-- Best Selling Products Grid -->
            <div class="row g-3 mb-4">
                @foreach($featuredProducts as $product)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="product-card-banduthu bg-white rounded shadow-sm h-100">
                        @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="discount-badge">
                            -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                        </span>
                        @endif

                        <div class="product-image-wrapper">
                            <a href="{{ route('products.show', $product->slug) }}">
                                @if($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                         class="product-image"
                                         alt="{{ $product->name }}">
                                @else
                                    <img src="https://via.placeholder.com/200"
                                         class="product-image"
                                         alt="{{ $product->name }}">
                                @endif
                            </a>
                        </div>

                        <div class="product-info p-2">
                            <h6 class="product-title small mb-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                                    {{ Str::limit($product->name, 45) }}
                                </a>
                            </h6>

                            <div class="product-rating mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-warning small"></i>
                                @endfor
                            </div>

                            <div class="product-price mb-2">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="price-old text-muted text-decoration-line-through small me-1">
                                        {{ number_format($product->price) }}₫
                                    </span>
                                    <br>
                                    <span class="price-sale text-danger fw-bold">
                                        {{ number_format($product->sale_price) }}₫
                                    </span>
                                @else
                                    <span class="price text-danger fw-bold">
                                        {{ number_format($product->price) }}₫
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('products.show', $product->slug) }}"
                               class="btn btn-danger btn-sm w-100">
                                Thêm vào giỏ
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- More Products -->
            @if($latestProducts->count() > 0)
            <div class="section-header bg-info text-white text-center py-3 rounded-top mb-3 mt-5">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-sparkles me-2"></i>Sản phẩm mới nhất
                </h5>
            </div>

            <div class="row g-3">
                @foreach($latestProducts->take(8) as $product)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="product-card-banduthu bg-white rounded shadow-sm h-100">
                        <div class="product-image-wrapper">
                            <a href="{{ route('products.show', $product->slug) }}">
                                @if($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                         class="product-image"
                                         alt="{{ $product->name }}">
                                @else
                                    <img src="https://via.placeholder.com/200"
                                         class="product-image"
                                         alt="{{ $product->name }}">
                                @endif
                            </a>
                        </div>

                        <div class="product-info p-2">
                            <h6 class="product-title small mb-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                                    {{ Str::limit($product->name, 45) }}
                                </a>
                            </h6>

                            <div class="product-price mb-2">
                                <span class="price text-danger fw-bold">
                                    {{ number_format($product->sale_price ?? $product->price) }}₫
                                </span>
                            </div>

                            <a href="{{ route('products.show', $product->slug) }}"
                               class="btn btn-outline-danger btn-sm w-100">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Bottom Banners -->
            <div class="row mt-5 mb-4">
                <div class="col-md-6 mb-3">
                    <div class="banner-bottom rounded overflow-hidden shadow">
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&h=200&fit=crop"
                             class="img-fluid" alt="Bottom Banner 1">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="banner-bottom rounded overflow-hidden shadow">
                        <img src="https://images.unsplash.com/photo-1509941943102-10c232535736?w=600&h=200&fit=crop"
                             class="img-fluid" alt="Bottom Banner 2">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Top Header Bar */
.top-header-bar {
    font-size: 0.85rem;
}

/* Categories Sidebar */
.categories-sidebar {
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.categories-header {
    font-size: 0.9rem;
    font-weight: 700;
    text-transform: uppercase;
}

.category-item {
    transition: all 0.3s ease;
}

.category-item:hover {
    background-color: #fff5f5;
    padding-left: 1.5rem !important;
}

.category-item:hover i {
    color: #dc3545;
}

/* Carousel */
.carousel-item {
    height: 400px;
}

.carousel-item img {
    height: 100%;
    object-fit: cover;
}

.carousel-caption {
    background: rgba(0, 0, 0, 0.5);
    padding: 20px;
    border-radius: 8px;
}

/* Promo Banners */
.promo-banner {
    transition: transform 0.3s ease;
}

.promo-banner:hover {
    transform: translateY(-5px);
}

/* Product Cards */
.product-card-banduthu {
    position: relative;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.product-card-banduthu:hover {
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-3px);
}

.discount-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #dc3545;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: bold;
    z-index: 10;
}

.product-image-wrapper {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1;
    background: #f8f9fa;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card-banduthu:hover .product-image {
    transform: scale(1.1);
}

.product-title {
    height: 2.5em;
    overflow: hidden;
    line-height: 1.25em;
}

.product-title a:hover {
    color: #dc3545 !important;
}

.product-rating i {
    font-size: 0.7rem;
}

.product-price {
    font-size: 0.9rem;
}

/* Section Headers */
.section-header {
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Services Box */
.services-box {
    border: 1px solid #f0f0f0;
}

.service-icon {
    width: 50px;
    text-align: center;
}

/* Hot Deals */
.deal-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .carousel-item {
        height: 250px;
    }

    .top-header-bar .col-md-6:last-child {
        display: none;
    }

    .categories-sidebar {
        margin-bottom: 20px;
    }
}

@media (max-width: 576px) {
    .product-title {
        font-size: 0.8rem;
    }

    .product-price {
        font-size: 0.85rem;
    }

    .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
}
</style>
@endpush
@endsection
