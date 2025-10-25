@extends('layouts.app')

@section('title', 'Sản phẩm - ' . setting('site_name', 'Bách Hóa Xanh'))

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
                            try {
                                $itemUrl = $item->url ?: ($item->route ? route($item->route) : '#');
                            } catch (\Exception $e) {
                                $itemUrl = '#';
                            }
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
                                        @php
                                            try {
                                                $childUrl = $child->url ?: ($child->route ? route($child->route) : '#');
                                            } catch (\Exception $e) {
                                                $childUrl = '#';
                                            }
                                        @endphp
                                        <li>
                                            <a class="dropdown-item" href="{{ $childUrl }}">
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
                <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm sản phẩm..." style="min-width: 250px;" value="{{ request('search') }}">
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
                                {{ \App\Cart::where('session_id', session()->getId())->sum('quantity') }}
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
                <small><i class="fas fa-shipping-fast me-1"></i>Giao hàng nhanh 2h Hà Nội</small>
            </div>
            <div class="col-md-6 text-md-end">
                <small>
                    <i class="fas fa-phone me-1"></i>Tư vấn: {{ setting('contact_phone', '1900-000-000') }} |
                    <i class="fas fa-store me-1"></i>Hệ thống cửa hàng
                </small>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Bộ lọc</h5>
                </div>
                <div class="card-body">
                    <!-- Search -->
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Tìm kiếm</label>
                            <input type="text" class="form-control border-success" name="search" value="{{ request('search') }}" placeholder="Nhập tên sản phẩm...">
                        </div>

                        <!-- Categories -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Danh mục</label>
                            <div class="list-group list-group-flush">
                                <a href="{{ route('products.index') }}"
                                   class="list-group-item list-group-item-action {{ !request('category') ? 'active bg-success text-white' : '' }}">
                                    Tất cả
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                       class="list-group-item list-group-item-action {{ request('category') == $category->slug ? 'active bg-success text-white' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-search me-2"></i>Tìm kiếm
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-success">
                    <i class="fas fa-box-open me-2"></i>Sản phẩm
                    <small class="text-muted fs-6">{{ $products->total() }} sản phẩm</small>
                </h2>
            </div>

            @if($products->count() > 0)
                <div class="row g-3">
                    @foreach($products as $product)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm product-card-bhx">
                            @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-danger">
                                    -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                </span>
                            </div>
                            @endif

                            <div class="card-img-top bg-light p-3" style="height: 250px; overflow: hidden;">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    @if($product->primaryImage)
                                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                             class="img-fluid w-100 h-100"
                                             style="object-fit: contain;"
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-image fa-4x text-muted"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <div class="card-body">
                                <div class="mb-2">
                                    <span class="badge bg-light text-success border border-success">{{ $product->category->name }}</span>
                                </div>

                                <h5 class="card-title">
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h5>

                                <p class="card-text text-muted small">
                                    {{ Str::limit($product->description, 100) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <div class="text-danger fw-bold fs-5">
                                                {{ number_format($product->sale_price) }}₫
                                            </div>
                                            <div class="text-muted small text-decoration-line-through">
                                                {{ number_format($product->price) }}₫
                                            </div>
                                        @else
                                            <div class="text-danger fw-bold fs-5">
                                                {{ number_format($product->price) }}₫
                                            </div>
                                        @endif
                                    </div>

                                    @if($product->stock > 0)
                                        <span class="badge bg-success">Còn hàng</span>
                                    @else
                                        <span class="badge bg-secondary">Hết hàng</span>
                                    @endif
                                </div>

                                <div class="d-grid gap-2">
                                    @if($product->stock > 0)
                                        <button class="btn btn-success btn-add-cart-bhx"
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}">
                                            <i class="fas fa-shopping-cart me-1"></i>Thêm vào giỏ
                                        </button>
                                    @else
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fas fa-times me-1"></i>Hết hàng
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Không tìm thấy sản phẩm nào.
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .product-card-bhx {
        transition: all 0.3s ease;
    }

    .product-card-bhx:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(15, 168, 58, 0.2) !important;
    }

    .top-green-header {
        background: linear-gradient(135deg, #0fa83a 0%, #0c8c31 100%);
    }

    .list-group-item.active {
        background-color: #0fa83a !important;
        border-color: #0fa83a !important;
    }

    .btn-success {
        background-color: #0fa83a;
        border-color: #0fa83a;
    }

    .btn-success:hover {
        background-color: #0c8c31;
        border-color: #0c8c31;
    }

    .text-success {
        color: #0fa83a !important;
    }

    .border-success {
        border-color: #0fa83a !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Add to cart functionality
    $('.btn-add-cart-bhx').click(function(e) {
        e.preventDefault();

        const button = $(this);
        const productId = button.data('product-id');
        const productName = button.data('product-name');

        // Disable button
        button.prop('disabled', true);
        const originalText = button.html();
        button.html('<i class="fas fa-spinner fa-spin me-1"></i>Đang thêm...');

        // Send AJAX request
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: 1
            },
            success: function(response) {
                console.log('Cart response:', response);

                if(response.success) {
                    // Update cart count in navbar
                    if(response.cart_count !== undefined) {
                        $('.cart-count').text(response.cart_count);
                        console.log('Cart count updated to:', response.cart_count);
                    }

                    // Show success message
                    button.html('<i class="fas fa-check me-1"></i>Đã thêm!');
                    button.removeClass('btn-success').addClass('btn-secondary');

                    // Show toast notification
                    if(typeof showToast === 'function') {
                        showToast('success', 'Đã thêm ' + productName + ' vào giỏ hàng', 'Thành công');
                    }

                    // Reset button after 2 seconds
                    setTimeout(function() {
                        button.html(originalText);
                        button.removeClass('btn-secondary').addClass('btn-success');
                        button.prop('disabled', false);
                    }, 2000);
                } else {
                    button.html(originalText);
                    button.prop('disabled', false);
                    showToast('danger', response.message || 'Có lỗi xảy ra!', 'Lỗi');
                }
            },
            error: function(xhr) {
                button.html(originalText);
                button.prop('disabled', false);

                if(xhr.status === 401) {
                    showToast('warning', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!', 'Cảnh báo');
                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 1500);
                } else {
                    showToast('danger', 'Có lỗi xảy ra! Vui lòng thử lại.', 'Lỗi');
                }
            }
        });
    });
});
</script>
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
                <div class="d-flex gap-3 mt-3 align-items-center">
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
                    @if(setting('social_tiktok'))
                    <a href="{{ setting('social_tiktok') }}" class="text-white" target="_blank">
                        <i class="fab fa-tiktok fa-2x"></i>
                    </a>
                    @endif
                    @if(setting('social_zalo'))
                    <a href="{{ setting('social_zalo') }}" target="_blank" title="Zalo" class="d-flex align-items-center">
                        <img src="{{ asset('images/social-icons/zalo.png') }}" alt="Zalo" style="width: 40px; height: 40px;">
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
