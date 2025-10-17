@extends('layouts.app')

@section('title', setting("site_name", "Shop VO") . ' - Trang chủ')

@section('content')
<!-- Hero Banner -->
<section class="hero-banner bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Chào mừng đến với <span class="text-warning">{{ setting('site_name', 'Shop VO') }}</span>
                </h1>
                <p class="lead mb-4">
                    {{ setting('site_description', 'Cửa hàng điện tử hàng đầu Việt Nam với các sản phẩm chất lượng và dịch vụ tốt nhất.') }}
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
                    </a>
                    <a href="#featured-products" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-star me-2"></i>Sản phẩm nổi bật
                    </a>
                </div>
                @if(setting('free_shipping_amount'))
                <div class="mt-4">
                    <div class="alert alert-warning d-inline-block">
                        <i class="fas fa-shipping-fast me-2"></i>
                        <strong>Miễn phí vận chuyển</strong> cho đơn hàng từ {{ number_format(setting('free_shipping_amount')) }}₫
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=500&h=400&fit=crop"
                     alt="Shopping" class="img-fluid rounded shadow" style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-4 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <i class="fas fa-box fa-2x text-primary mb-2"></i>
                    <h4 class="fw-bold">{{ App\Product::where('status', true)->count() }}+</h4>
                    <p class="text-muted">Sản phẩm</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <i class="fas fa-users fa-2x text-success mb-2"></i>
                    <h4 class="fw-bold">{{ App\User::count() }}+</h4>
                    <p class="text-muted">Khách hàng</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <i class="fas fa-shopping-cart fa-2x text-info mb-2"></i>
                    <h4 class="fw-bold">{{ App\Order::count() }}+</h4>
                    <p class="text-muted">Đơn hàng</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <i class="fas fa-star fa-2x text-warning mb-2"></i>
                    <h4 class="fw-bold">4.9/5</h4>
                    <p class="text-muted">Đánh giá</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="categories-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Danh mục sản phẩm</h2>
            <p class="text-muted">Khám phá các danh mục sản phẩm đa dạng của chúng tôi</p>
        </div>
        <div class="row">
            @foreach($categories as $category)
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <a href="{{ route('products.index', ['category' => $category->id]) }}"
                   class="text-decoration-none">
                    <div class="category-card text-center p-4 h-100 border rounded hover-shadow transition">
                        <div class="category-icon mb-3">
                            @switch($category->slug)
                                @case('dien-thoai-tablet')
                                    <i class="fas fa-mobile-alt fa-3x text-primary"></i>
                                    @break
                                @case('laptop-may-tinh')
                                    <i class="fas fa-laptop fa-3x text-success"></i>
                                    @break
                                @case('phu-kien-cong-nghe')
                                    <i class="fas fa-headphones fa-3x text-info"></i>
                                    @break
                                @case('gia-dung-dien-may')
                                    <i class="fas fa-blender fa-3x text-warning"></i>
                                    @break
                                @case('thoi-trang')
                                    <i class="fas fa-tshirt fa-3x text-danger"></i>
                                    @break
                                @default
                                    <i class="fas fa-tag fa-3x text-secondary"></i>
                            @endswitch
                        </div>
                        <h6 class="fw-bold mb-2">{{ $category->name }}</h6>
                        <p class="text-muted small mb-0">{{ $category->products_count }} sản phẩm</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section id="featured-products" class="featured-products py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Sản phẩm nổi bật</h2>
            <p class="text-muted">Những sản phẩm được yêu thích nhất tại Shop VO</p>
        </div>
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product-card card h-100 border-0 shadow-sm hover-shadow transition">
                    <div class="position-relative">
                        <img src="{{ $product->primary_image_url }}"
                             alt="{{ $product->name }}"
                             class="card-img-top"
                             style="height: 200px; object-fit: cover;">

                        @if($product->sale_price < $product->price)
                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                            -{{ $product->discount_percent }}%
                        </span>
                        @endif

                        <span class="badge bg-primary position-absolute top-0 end-0 m-2">
                            <i class="fas fa-star me-1"></i>Nổi bật
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                        <p class="text-muted small mb-2">{{ $product->category->name }}</p>

                        <div class="price-section mt-auto">
                            @if($product->sale_price < $product->price)
                            <div class="d-flex align-items-center gap-2">
                                <span class="h6 text-danger fw-bold mb-0">{{ number_format($product->sale_price) }}₫</span>
                                <span class="text-muted text-decoration-line-through small">{{ number_format($product->price) }}₫</span>
                            </div>
                            @else
                            <span class="h6 text-primary fw-bold">{{ number_format($product->price) }}₫</span>
                            @endif
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <a href="{{ route('products.show', $product) }}"
                               class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                            </a>
                            <button class="btn btn-primary btn-sm" onclick="addToCart({{ $product->id }})">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-th me-2"></i>Xem tất cả sản phẩm
            </a>
        </div>
    </div>
</section>
@endif

<!-- Latest Products Section -->
@if($latestProducts->count() > 0)
<section class="latest-products py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Sản phẩm mới nhất</h2>
            <p class="text-muted">Cập nhật những sản phẩm mới nhất từ Shop VO</p>
        </div>
        <div class="row">
            @foreach($latestProducts->take(8) as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product-card card h-100 border-0 shadow-sm hover-shadow transition">
                    <div class="position-relative">
                        <img src="{{ $product->primary_image_url }}"
                             alt="{{ $product->name }}"
                             class="card-img-top"
                             style="height: 200px; object-fit: cover;">

                        <span class="badge bg-success position-absolute top-0 start-0 m-2">
                            <i class="fas fa-sparkles me-1"></i>Mới
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                        <p class="text-muted small mb-2">{{ $product->category->name }}</p>

                        <div class="price-section mt-auto">
                            @if($product->sale_price < $product->price)
                            <div class="d-flex align-items-center gap-2">
                                <span class="h6 text-danger fw-bold mb-0">{{ number_format($product->sale_price) }}₫</span>
                                <span class="text-muted text-decoration-line-through small">{{ number_format($product->price) }}₫</span>
                            </div>
                            @else
                            <span class="h6 text-primary fw-bold">{{ number_format($product->price) }}₫</span>
                            @endif
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <a href="{{ route('products.show', $product) }}"
                               class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                            </a>
                            <button class="btn btn-primary btn-sm" onclick="addToCart({{ $product->id }})">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Best Sellers Section -->
@if($bestSellers->count() > 0)
<section class="best-sellers py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Sản phẩm bán chạy</h2>
            <p class="text-muted">Những sản phẩm được khách hàng lựa chọn nhiều nhất</p>
        </div>
        <div class="row">
            @foreach($bestSellers as $index => $product)
            <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                <div class="product-card card h-100 border-0 shadow-sm hover-shadow transition">
                    <div class="position-relative">
                        <img src="{{ $product->primary_image_url }}"
                             alt="{{ $product->name }}"
                             class="card-img-top"
                             style="height: 150px; object-fit: cover;">

                        <span class="badge bg-warning position-absolute top-0 start-0 m-2">
                            <i class="fas fa-fire me-1"></i>#{{ $index + 1 }}
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold small">{{ Str::limit($product->name, 20) }}</h6>
                        <p class="text-muted small mb-2">{{ $product->category->name }}</p>

                        <div class="price-section mt-auto">
                            <span class="h6 text-primary fw-bold small">{{ number_format($product->sale_price ?: $product->price) }}₫</span>
                        </div>

                        <div class="mt-2">
                            <a href="{{ route('products.show', $product) }}"
                               class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>Xem
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Newsletter Section -->
<section class="newsletter py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-3">Đăng ký nhận tin tức</h3>
                <p class="mb-0">Nhận thông tin về sản phẩm mới và ưu đãi đặc biệt</p>
            </div>
            <div class="col-lg-6">
                <form class="d-flex gap-2">
                    <input type="email" class="form-control" placeholder="Nhập email của bạn..." required>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane me-1"></i>Đăng ký
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.hero-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.category-card:hover {
    background-color: #f8f9fa;
}

.transition {
    transition: all 0.3s ease;
}

.product-card .card-img-top {
    transition: transform 0.3s ease;
}

.product-card:hover .card-img-top {
    transform: scale(1.05);
}

.stat-item {
    padding: 1rem;
}

.badge {
    font-size: 0.7rem;
}
</style>
@endpush

@push('scripts')
<script>
function addToCart(productId) {
    // Implement add to cart functionality
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            updateCartCount();

            // Show success message
            const toast = `
                <div class="toast align-items-center text-white bg-success border-0" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-check-circle me-2"></i>Đã thêm sản phẩm vào giỏ hàng!
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', toast);

            // Show toast
            const toastElement = document.querySelector('.toast:last-child');
            const bsToast = new bootstrap.Toast(toastElement);
            bsToast.show();

            // Remove toast after it's hidden
            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.querySelector('.cart-count');
            if (cartBadge) {
                cartBadge.textContent = data.count;
                if (data.count > 0) {
                    cartBadge.style.display = 'inline-block';
                } else {
                    cartBadge.style.display = 'none';
                }
            }
        });
}
</script>
@endpush
