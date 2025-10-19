@extends('layouts.app')

@section('title', setting("site_name", "Bách Hóa Xanh") . ' - Trang chủ')

@section('navbar')
<!-- Bach Hoa Xanh Custom Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #0fa83a 0%, #0c8c31 100%);">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            @if(setting('site_logo'))
                <img src="{{ setting('site_logo') }}" alt="{{ setting('site_name', 'Bách Hóa Xanh') }}" style="height: 40px;" class="me-2">
            @else
                <i class="fas fa-leaf me-2"></i>
            @endif
            {{ setting('site_name', 'Bách Hóa Xanh') }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Dynamic Menu -->
            @php
                $headerMenu = \App\Menu::where('location', 'header')
                    ->where('is_active', true)
                    ->with(['items' => function($query) {
                        $query->whereNull('parent_id')->where('is_active', true)->orderBy('order');
                    }, 'items.children' => function($query) {
                        $query->where('is_active', true)->orderBy('order');
                    }])
                    ->first();
            @endphp

            @if($headerMenu && $headerMenu->items->count() > 0)
                <ul class="navbar-nav me-auto">
                    @foreach($headerMenu->items as $item)
                        @php
                            $hasChildren = $item->children->where('is_active', true)->count() > 0;
                            $itemUrl = $item->url ?: ($item->route ? route($item->route) : '#');
                            $isActive = request()->url() === $itemUrl || ($item->route && request()->routeIs($item->route));
                        @endphp
                        <li class="nav-item{{ $hasChildren ? ' dropdown' : '' }}">
                            @if($hasChildren)
                                <a class="nav-link dropdown-toggle{{ $isActive ? ' active' : '' }}" 
                                   href="{{ $itemUrl }}" 
                                   role="button" 
                                   data-bs-toggle="dropdown">
                                    @if($item->icon)<i class="{{ $item->icon }} me-1"></i>@endif
                                    {{ $item->title }}
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach($item->children->where('is_active', true)->sortBy('order') as $child)
                                        <li>
                                            <a class="dropdown-item" 
                                               href="{{ $child->url ?: ($child->route ? route($child->route) : '#') }}">
                                                @if($child->icon)<i class="{{ $child->icon }} me-1"></i>@endif
                                                {{ $child->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <a class="nav-link{{ $isActive ? ' active' : '' }}" href="{{ $itemUrl }}">
                                    @if($item->icon)<i class="{{ $item->icon }} me-1"></i>@endif
                                    {{ $item->title }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

            <!-- Search -->
            <form class="d-flex me-3" method="GET" action="{{ route('products.index') }}">
                <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm sản phẩm..." style="min-width: 250px;">
                <button class="btn btn-light" type="submit">
                    <i class="fas fa-search text-success"></i>
                </button>
            </form>

            <!-- Right Side -->
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                        </a>
                    </li>
                @else
                    <li class="nav-item me-2">
                        <a href="{{ route('cart.index') }}" class="btn btn-light position-relative">
                            <i class="fas fa-shopping-cart text-success"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                                0
                            </span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::user()->is_admin)
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Quản trị
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
@endsection

@section('content')
<!-- Top Green Header -->
<div class="top-green-header bg-success text-white py-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <small>
                    <i class="fas fa-map-marker-alt me-2"></i>Chọn vị trí giao gần bạn
                </small>
            </div>
            <div class="col-md-6 text-end">
                <a href="#" class="text-white text-decoration-none me-3">
                    <i class="fas fa-user me-1"></i>Đăng nhập
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-0">
    <div class="container">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-lg-2 col-md-3 d-none d-md-block">
                <div class="sidebar-bhx mt-3">
                    <!-- Categories Menu -->
                    <div class="category-menu-bhx">
                        <div class="category-header-bhx bg-success text-white p-2 rounded-top">
                            <i class="fas fa-bars me-2"></i>
                            <strong>DANH MỤC SẢN PHẨM</strong>
                        </div>

                        <div class="category-list-bhx bg-white">
                            @foreach($categories as $index => $category)
                            <a href="{{ route('products.index', ['category' => $category->id]) }}"
                               class="category-item-bhx d-flex align-items-center p-2 text-decoration-none {{ $index < count($categories) - 1 ? 'border-bottom' : '' }}">
                                <i class="fas fa-angle-right me-2 text-success"></i>
                                <span class="text-dark small">{{ $category->name }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Hot Promo -->
                    <div class="hot-promo-bhx mt-3 bg-danger text-white rounded text-center p-3">
                        <i class="fas fa-bolt fa-2x mb-2"></i>
                        <h6 class="fw-bold mb-1">KHUYẾN MÃI SỐC</h6>
                        <small>Giảm đến 50%</small>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 col-md-9">
                <!-- Flash Sale Banner -->
                <div class="flash-sale-banner mt-3 mb-3">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <div class="flash-badge bg-danger text-white rounded p-2 text-center h-100 d-flex align-items-center justify-content-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">FLASH</h5>
                                    <h5 class="mb-0 fw-bold">SALE</h5>
                                </div>
                            </div>
                        </div>
                        @foreach(['Giá sốc cuối tuần', 'Nước giải khát', 'Gạo, nếp', 'Mì ăn liền', 'Nước suối', 'Sữa chua', 'Rau lá', 'Nước trà'] as $index => $item)
                        <div class="col">
                            <div class="quick-cat-item bg-white rounded text-center p-2 border h-100">
                                @if($index == 0)
                                    <div class="badge bg-danger text-white mb-1">-33%</div>
                                @elseif($index == 1)
                                    <div class="badge bg-success text-white mb-1">-27%</div>
                                @endif
                                <div class="cat-icon mb-1">
                                    <img src="https://via.placeholder.com/50" class="img-fluid" alt="{{ $item }}">
                                </div>
                                <small class="d-block text-truncate">{{ $item }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Category Pills -->
                <div class="category-pills mb-3">
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge bg-success text-white px-3 py-2">Món mặn</span>
                        <span class="badge bg-light text-dark px-3 py-2">Món xào, lược</span>
                        <span class="badge bg-light text-dark px-3 py-2">Món canh</span>
                        <span class="badge bg-light text-dark px-3 py-2">Rau sống, nếm</span>
                        <span class="badge bg-light text-dark px-3 py-2">Trái cây</span>
                        <span class="badge bg-light text-dark px-3 py-2">Đồ ăn nhanh</span>
                        <span class="badge bg-light text-dark px-3 py-2">Tráng miệng</span>
                    </div>
                </div>

                <!-- Featured Section -->
                <div class="featured-section-bhx mb-4">
                    <div class="section-title-bhx bg-success text-white p-3 rounded-top">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-shopping-basket me-2"></i>ĐI CHỢ MỖI NGÀY
                        </h5>
                    </div>

                    <!-- Main Dishes Section -->
                    <div class="products-grid-bhx bg-white p-3 rounded-bottom">
                        <div class="row g-3">
                            @foreach($featuredProducts as $product)
                            <div class="col-lg-2 col-md-3 col-4">
                                <div class="product-card-bhx bg-white border rounded h-100">
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                    <div class="product-badge-bhx">
                                        <span class="badge bg-danger">
                                            -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                        </span>
                                    </div>
                                    @endif

                                    <div class="product-image-bhx">
                                        <a href="{{ route('products.show', $product->slug) }}">
                                            @if($product->primaryImage)
                                                <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                                     class="img-fluid"
                                                     alt="{{ $product->name }}">
                                            @else
                                                <img src="https://via.placeholder.com/150"
                                                     class="img-fluid"
                                                     alt="{{ $product->name }}">
                                            @endif
                                        </a>
                                    </div>

                                    <div class="product-info-bhx p-2">
                                        <h6 class="product-name-bhx small mb-2">
                                            <a href="{{ route('products.show', $product->slug) }}"
                                               class="text-dark text-decoration-none">
                                                {{ Str::limit($product->name, 40) }}
                                            </a>
                                        </h6>

                                        <div class="product-price-bhx mb-2">
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <div class="price-sale-bhx text-danger fw-bold">
                                                    {{ number_format($product->sale_price) }}₫
                                                </div>
                                                <div class="price-old-bhx text-muted small text-decoration-line-through">
                                                    {{ number_format($product->price) }}₫
                                                </div>
                                            @else
                                                <div class="price-bhx text-danger fw-bold">
                                                    {{ number_format($product->price) }}₫
                                                </div>
                                            @endif
                                        </div>

                                        <button class="btn btn-success btn-sm w-100 btn-add-cart-bhx">
                                            MUA
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Fresh Products Section -->
                @if($latestProducts->count() > 0)
                <div class="fresh-section-bhx mb-4">
                    <div class="section-title-bhx bg-light border-start border-5 border-success p-3 mb-3">
                        <h5 class="mb-0 fw-bold text-success">
                            <i class="fas fa-leaf me-2"></i>MUA NGUYÊN LIỆU
                        </h5>
                    </div>

                    <div class="row g-3">
                        @foreach($latestProducts->take(12) as $product)
                        <div class="col-lg-2 col-md-3 col-4">
                            <div class="product-card-bhx bg-white border rounded h-100">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                <div class="product-badge-bhx">
                                    <span class="badge bg-danger">
                                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                    </span>
                                </div>
                                @endif

                                <div class="product-image-bhx">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        @if($product->primaryImage)
                                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                                 class="img-fluid"
                                                 alt="{{ $product->name }}">
                                        @else
                                            <img src="https://via.placeholder.com/150"
                                                 class="img-fluid"
                                                 alt="{{ $product->name }}">
                                        @endif
                                    </a>
                                </div>

                                <div class="product-info-bhx p-2">
                                    <h6 class="product-name-bhx small mb-2">
                                        <a href="{{ route('products.show', $product->slug) }}"
                                           class="text-dark text-decoration-none">
                                            {{ Str::limit($product->name, 40) }}
                                        </a>
                                    </h6>

                                    <div class="product-price-bhx mb-2">
                                        <div class="price-bhx text-danger fw-bold">
                                            {{ number_format($product->sale_price ?? $product->price) }}₫
                                        </div>
                                    </div>

                                    <button class="btn btn-success btn-sm w-100 btn-add-cart-bhx">
                                        MUA
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Best Sellers Section -->
                @if($bestSellers->count() > 0)
                <div class="bestseller-section-bhx mb-4">
                    <div class="section-title-bhx bg-warning text-dark p-3 mb-3 rounded">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-fire me-2"></i>SẢN PHẨM BÁN CHẠY
                        </h5>
                    </div>

                    <div class="row g-3">
                        @foreach($bestSellers->take(6) as $product)
                        <div class="col-lg-2 col-md-3 col-4">
                            <div class="product-card-bhx bg-white border rounded h-100">
                                <div class="product-image-bhx">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        @if($product->primaryImage)
                                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                                 class="img-fluid"
                                                 alt="{{ $product->name }}">
                                        @else
                                            <img src="https://via.placeholder.com/150"
                                                 class="img-fluid"
                                                 alt="{{ $product->name }}">
                                        @endif
                                    </a>
                                </div>

                                <div class="product-info-bhx p-2">
                                    <h6 class="product-name-bhx small mb-2">
                                        <a href="{{ route('products.show', $product->slug) }}"
                                           class="text-dark text-decoration-none">
                                            {{ Str::limit($product->name, 40) }}
                                        </a>
                                    </h6>

                                    <div class="product-price-bhx mb-2">
                                        <div class="price-bhx text-danger fw-bold">
                                            {{ number_format($product->sale_price ?? $product->price) }}₫
                                        </div>
                                    </div>

                                    <button class="btn btn-success btn-sm w-100 btn-add-cart-bhx">
                                        MUA
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Top Green Header */
.top-green-header {
    background: linear-gradient(135deg, #0fa83a 0%, #0c8c31 100%);
    font-size: 0.9rem;
}

/* Sidebar */
.sidebar-bhx {
    position: sticky;
    top: 20px;
}

.category-menu-bhx {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.category-header-bhx {
    background: linear-gradient(135deg, #0fa83a 0%, #0c8c31 100%);
    font-size: 0.85rem;
}

.category-list-bhx {
    max-height: 500px;
    overflow-y: auto;
}

.category-item-bhx {
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.category-item-bhx:hover {
    background-color: #f0fdf4;
    padding-left: 1rem !important;
}

.category-item-bhx:hover i {
    color: #0fa83a;
}

.hot-promo-bhx {
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

/* Flash Sale Banner */
.flash-sale-banner {
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.flash-badge {
    min-height: 80px;
}

.quick-cat-item {
    transition: transform 0.3s ease;
    cursor: pointer;
}

.quick-cat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.cat-icon img {
    width: 50px;
    height: 50px;
    object-fit: contain;
}

/* Category Pills */
.category-pills .badge {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.category-pills .badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.category-pills .bg-success {
    background: linear-gradient(135deg, #0fa83a 0%, #0c8c31 100%) !important;
}

/* Section Titles */
.section-title-bhx {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.section-title-bhx.bg-success {
    background: linear-gradient(135deg, #0fa83a 0%, #0c8c31 100%) !important;
}

/* Product Cards */
.product-card-bhx {
    position: relative;
    transition: all 0.3s ease;
    overflow: hidden;
}

.product-card-bhx:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    transform: translateY(-3px);
}

.product-badge-bhx {
    position: absolute;
    top: 8px;
    left: 8px;
    z-index: 10;
}

.product-image-bhx {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.product-image-bhx img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card-bhx:hover .product-image-bhx img {
    transform: scale(1.1);
}

.product-name-bhx {
    height: 2.5em;
    overflow: hidden;
    line-height: 1.25em;
    font-size: 0.85rem;
}

.product-name-bhx a:hover {
    color: #0fa83a !important;
}

.product-price-bhx {
    font-size: 0.9rem;
}

.price-sale-bhx {
    font-size: 1rem;
}

.price-old-bhx {
    font-size: 0.8rem;
}

.btn-add-cart-bhx {
    background: linear-gradient(135deg, #0fa83a 0%, #0c8c31 100%);
    border: none;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 6px 12px;
    transition: all 0.3s ease;
}

.btn-add-cart-bhx:hover {
    background: linear-gradient(135deg, #0c8c31 0%, #0a7329 100%);
    transform: scale(1.05);
}

/* Products Grid */
.products-grid-bhx {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Responsive */
@media (max-width: 992px) {
    .sidebar-bhx {
        position: static;
    }
}

@media (max-width: 768px) {
    .flash-sale-banner .col {
        flex: 0 0 auto;
        width: 20%;
    }

    .quick-cat-item small {
        font-size: 0.7rem;
    }

    .cat-icon img {
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 576px) {
    .product-name-bhx {
        font-size: 0.75rem;
        height: 2.2em;
    }

    .product-price-bhx {
        font-size: 0.8rem;
    }

    .price-sale-bhx {
        font-size: 0.85rem;
    }

    .btn-add-cart-bhx {
        font-size: 0.75rem;
        padding: 4px 8px;
    }

    .flash-sale-banner .col {
        width: 25%;
    }
}
</style>
@endpush
@endsection

@section('footer')
<!-- Bach Hoa Xanh Custom Footer -->
<footer class="mt-5 text-white" style="background: linear-gradient(135deg, #0fa83a 0%, #0c8c31 100%);">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <h5 class="fw-bold">
                    @if(setting('site_logo'))
                        <img src="{{ setting('site_logo') }}" alt="{{ setting('site_name', 'Bách Hóa Xanh') }}" style="height: 30px;" class="me-2">
                    @else
                        <i class="fas fa-leaf me-2"></i>
                    @endif
                    {{ setting('site_name', 'Bách Hóa Xanh') }}
                </h5>
                <p class="mt-3">{{ setting('site_description', 'Thực phẩm tươi sống, đảm bảo chất lượng') }}</p>
                <p><strong>Giờ làm việc:</strong><br>{{ setting('business_hours', 'Thứ 2 - Chủ nhật: 6:00 - 22:00') }}</p>
            </div>
            <div class="col-md-4">
                <h5 class="fw-bold">Liên hệ</h5>
                <p class="mt-3"><i class="fas fa-phone me-2"></i>{{ setting('contact_phone', '1900-000-000') }}</p>
                <p><i class="fas fa-envelope me-2"></i>{{ setting('contact_email', 'support@bachoaxanh.com') }}</p>
                <p><i class="fas fa-map-marker-alt me-2"></i>{{ setting('contact_address', 'Hệ thống siêu thị toàn quốc') }}</p>
            </div>
            <div class="col-md-4">
                <h5 class="fw-bold">Theo dõi chúng tôi</h5>
                <div class="d-flex gap-3 mt-3">
                    @if(setting('social_facebook'))
                    <a href="{{ setting('social_facebook') }}" class="text-white" target="_blank">
                        <i class="fab fa-facebook fa-2x"></i>
                    </a>
                    @endif
                    @if(setting('social_instagram'))
                    <a href="{{ setting('social_instagram') }}" class="text-white" target="_blank">
                        <i class="fab fa-instagram fa-2x"></i>
                    </a>
                    @endif
                    @if(setting('social_youtube'))
                    <a href="{{ setting('social_youtube') }}" class="text-white" target="_blank">
                        <i class="fab fa-youtube fa-2x"></i>
                    </a>
                    @endif
                </div>
                @if(setting('free_shipping_amount'))
                <div class="mt-3 p-3 rounded" style="background: rgba(255,255,255,0.15);">
                    <p class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Giao hàng nhanh 2h - Miễn phí từ {{ number_format(setting('free_shipping_amount')) }}₫</p>
                </div>
                @endif
            </div>
        </div>
        <hr class="mt-4 mb-3" style="border-color: rgba(255,255,255,0.2);">
        <div class="text-center">
            <p class="mb-0">&copy; 2025 {{ setting('site_name', 'Bách Hóa Xanh') }}. All rights reserved.</p>
        </div>
    </div>
</footer>
@endsection
