@extends('layouts.app', ['seoModel' => $product])

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Sản phẩm</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="text-decoration-none">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="product-images">
                @if($product->images->count() > 0)
                    @php
                        $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                    @endphp

                    <!-- Main Image -->
                    <div class="main-image mb-3">
                        <img id="mainProductImage"
                             src="{{ $primaryImage->image_url }}"
                             alt="{{ $primaryImage->alt_text }}"
                             class="img-fluid rounded shadow"
                             style="width: 100%; max-height: 500px; object-fit: cover;">
                    </div>

                    <!-- Thumbnail Gallery -->
                    @if($product->images->count() > 1)
                    <div class="thumbnail-gallery">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($product->images as $image)
                            <img src="{{ $image->image_url }}"
                                 alt="{{ $image->alt_text }}"
                                 class="img-thumbnail thumbnail-img {{ $image->is_primary ? 'active-thumbnail' : '' }}"
                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                 onclick="changeMainImage('{{ $image->image_url }}', '{{ $image->alt_text }}', this)">
                            @endforeach
                        </div>
                    </div>
                    @endif
                @elseif($product->image)
                    <div class="main-image">
                        <img src="{{ $product->image }}"
                             alt="{{ $product->name }}"
                             class="img-fluid rounded shadow"
                             style="width: 100%; max-height: 500px; object-fit: cover;">
                    </div>
                @else
                    <div class="no-image bg-light rounded d-flex align-items-center justify-content-center"
                         style="height: 400px;">
                        <div class="text-center text-muted">
                            <i class="fas fa-image fa-4x mb-3"></i>
                            <p>Không có hình ảnh</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <div class="product-info">
                <!-- Product Title -->
                <h1 class="h2 fw-bold mb-3">{{ $product->name }}</h1>

                <!-- Product Meta -->
                <div class="product-meta mb-4">
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <span class="badge bg-info">{{ $product->category->name }}</span>
                        @if($product->sku)
                            <span class="text-muted small">SKU: {{ $product->sku }}</span>
                        @endif
                        @if($product->featured)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>Nổi bật
                            </span>
                        @endif
                        @if($product->stock <= 10)
                            <span class="badge bg-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>Sắp hết hàng
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Price -->
                <div class="product-price mb-4">
                    @if($product->sale_price && $product->sale_price < $product->price)
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="h3 text-danger fw-bold mb-0">{{ number_format($product->sale_price) }}₫</span>
                        <span class="h5 text-muted text-decoration-line-through mb-0">{{ number_format($product->price) }}₫</span>
                        <span class="badge bg-danger fs-6">-{{ $product->discount_percent }}%</span>
                    </div>
                    @else
                    <span class="h3 text-primary fw-bold">{{ number_format($product->price) }}₫</span>
                    @endif
                </div>

                <!-- Stock Status -->
                <div class="stock-status mb-4">
                    @if($product->stock > 0)
                        <div class="d-flex align-items-center text-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <span class="fw-semibold">Còn hàng ({{ $product->stock }} sản phẩm)</span>
                        </div>
                    @else
                        <div class="d-flex align-items-center text-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <span class="fw-semibold">Hết hàng</span>
                        </div>
                    @endif
                </div>

                <!-- Add to Cart Form -->
                @if($product->stock > 0)
                <form id="addToCartForm" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="row align-items-end">
                        <div class="col-md-4 col-6 mb-3">
                            <label for="quantity" class="form-label fw-semibold">Số lượng:</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity(-1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center" id="quantity" name="quantity"
                                       value="1" min="1" max="{{ $product->stock }}" readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity(1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-8 col-6 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100" id="addToCartBtn">
                                <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                            </button>
                        </div>
                    </div>
                </form>
                @else
                <div class="mb-4">
                    <button class="btn btn-secondary btn-lg w-100" disabled>
                        <i class="fas fa-times me-2"></i>Hết hàng
                    </button>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="quick-actions d-flex gap-2 mb-4">
                    @include('components.wishlist-button', ['product' => $product, 'class' => 'flex-fill'])
                    <button class="btn btn-outline-info flex-fill" onclick="shareProduct()">
                        <i class="fas fa-share-alt me-2"></i>Chia sẻ
                    </button>
                </div>

                <!-- Product Description -->
                @if($product->description)
                <div class="product-description">
                    <h5 class="fw-bold mb-3">Mô tả sản phẩm</h5>
                    <div class="bg-light p-3 rounded">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details"
                            type="button" role="tab">Thông tin chi tiết</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping"
                            type="button" role="tab">Vận chuyển</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                            type="button" role="tab">Đánh giá</button>
                </li>
            </ul>

            <div class="tab-content pt-4" id="productTabsContent">
                <!-- Details Tab -->
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold">Tên sản phẩm:</td>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Danh mục:</td>
                                    <td>{{ $product->category->name }}</td>
                                </tr>
                                @if($product->sku)
                                <tr>
                                    <td class="fw-semibold">SKU:</td>
                                    <td>{{ $product->sku }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="fw-semibold">Tình trạng:</td>
                                    <td>
                                        @if($product->stock > 0)
                                            <span class="text-success">Còn hàng</span>
                                        @else
                                            <span class="text-danger">Hết hàng</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold">Giá:</td>
                                    <td class="text-primary fw-bold">{{ number_format($product->sale_price ?: $product->price) }}₫</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Số lượng:</td>
                                    <td>{{ $product->stock }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Ngày tạo:</td>
                                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Shipping Tab -->
                <div class="tab-pane fade" id="shipping" role="tabpanel">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Thông tin vận chuyển</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-truck text-primary me-2"></i>
                                    <strong>Giao hàng nhanh:</strong> 1-2 ngày làm việc
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-shield-alt text-success me-2"></i>
                                    <strong>Bảo hành:</strong> 12 tháng chính hãng
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-undo text-info me-2"></i>
                                    <strong>Đổi trả:</strong> 7 ngày miễn phí
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-credit-card text-warning me-2"></i>
                                    <strong>Thanh toán:</strong> COD, Chuyển khoản, Thẻ
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="text-center py-5">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <h5>Chưa có đánh giá</h5>
                        <p class="text-muted">Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                        <button class="btn btn-primary">Viết đánh giá</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Sản phẩm liên quan</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="position-relative">
                            <img src="{{ $relatedProduct->primary_image_url }}"
                                 alt="{{ $relatedProduct->name }}"
                                 class="card-img-top"
                                 style="height: 200px; object-fit: cover;">

                            @if($relatedProduct->sale_price < $relatedProduct->price)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                -{{ $relatedProduct->discount_percent }}%
                            </span>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title fw-bold">{{ Str::limit($relatedProduct->name, 50) }}</h6>
                            <p class="text-muted small mb-2">{{ $relatedProduct->category->name }}</p>

                            <div class="price-section mt-auto">
                                @if($relatedProduct->sale_price < $relatedProduct->price)
                                <div class="d-flex align-items-center gap-2">
                                    <span class="h6 text-danger fw-bold mb-0">{{ number_format($relatedProduct->sale_price) }}₫</span>
                                    <span class="text-muted text-decoration-line-through small">{{ number_format($relatedProduct->price) }}₫</span>
                                </div>
                                @else
                                <span class="h6 text-primary fw-bold">{{ number_format($relatedProduct->price) }}₫</span>
                                @endif
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('products.show', $relatedProduct) }}"
                                   class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Reviews Section -->
    @include('components.reviews', ['product' => $product])
</div>
@endsection

@push('styles')
<style>
.thumbnail-img {
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.thumbnail-img:hover,
.thumbnail-img.active-thumbnail {
    border-color: #007bff;
}

.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.product-images .main-image img {
    transition: transform 0.3s ease;
}

.product-images .main-image:hover img {
    transform: scale(1.02);
}

.quantity-controls .btn {
    width: 40px;
    height: 40px;
}
</style>
@endpush

@push('scripts')
<script>
// Change main product image
function changeMainImage(imageUrl, altText, thumbnail) {
    const mainImage = document.getElementById('mainProductImage');
    if (mainImage) {
        mainImage.src = imageUrl;
        mainImage.alt = altText;
    }

    // Update active thumbnail
    document.querySelectorAll('.thumbnail-img').forEach(img => {
        img.classList.remove('active-thumbnail');
    });
    thumbnail.classList.add('active-thumbnail');
}

// Change quantity
function changeQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    let newValue = currentValue + change;

    const min = parseInt(quantityInput.min);
    const max = parseInt(quantityInput.max);

    if (newValue >= min && newValue <= max) {
        quantityInput.value = newValue;
    }
}

// Add to cart
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const button = document.getElementById('addToCartBtn');
    const originalText = button.innerHTML;

    // Disable button and show loading
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang thêm...';

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('success', 'Đã thêm sản phẩm vào giỏ hàng!');

            // Update cart count
            updateCartCount();

            // Reset quantity to 1
            document.getElementById('quantity').value = 1;
        } else {
            showToast('error', data.message || 'Có lỗi xảy ra!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Có lỗi xảy ra khi thêm sản phẩm!');
    })
    .finally(() => {
        // Re-enable button
        button.disabled = false;
        button.innerHTML = originalText;
    });
});

// Add to wishlist
function addToWishlist(productId) {
    showToast('info', 'Tính năng yêu thích sẽ được cập nhật sớm!');
}

// Share product
function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $product->name }}',
            text: 'Xem sản phẩm này tại Shop VO',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            showToast('success', 'Đã sao chép link sản phẩm!');
        });
    }
}

// Show toast notification
function showToast(type, message) {
    const bgClass = type === 'success' ? 'bg-success' :
                   type === 'error' ? 'bg-danger' : 'bg-info';

    const toast = `
        <div class="toast align-items-center text-white ${bgClass} border-0" role="alert"
             style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', toast);

    const toastElement = document.querySelector('.toast:last-child');
    const bsToast = new bootstrap.Toast(toastElement);
    bsToast.show();

    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

// Update cart count
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
