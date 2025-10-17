@extends('layouts.app')

@section('title', 'Danh sách yêu thích')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Danh sách yêu thích</h1>
        <span class="badge bg-primary fs-6">{{ $wishlists->total() }} sản phẩm</span>
    </div>

    @if($wishlists->count() > 0)
    <div class="row">
        @foreach($wishlists as $wishlistItem)
        @php $product = $wishlistItem->product; @endphp
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 product-card">
                <div class="position-relative">
                    @if($product->primaryImage)
                    <img src="{{ $product->primaryImage->image_url }}"
                         class="card-img-top"
                         alt="{{ $product->name }}"
                         style="height: 200px; object-fit: cover;">
                    @elseif($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         class="card-img-top"
                         alt="{{ $product->name }}"
                         style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                         style="height: 200px;">
                        <i class="fas fa-image text-muted fa-3x"></i>
                    </div>
                    @endif

                    <!-- Discount Badge -->
                    @if($product->sale_price < $product->price)
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                        -{{ $product->discount_percent }}%
                    </span>
                    @endif

                    <!-- Wishlist Button -->
                    <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-wishlist"
                            data-product-id="{{ $product->id }}"
                            title="Xóa khỏi yêu thích">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">{{ Str::limit($product->name, 50) }}</h6>
                    <p class="text-muted small mb-2">{{ $product->category->name }}</p>

                    <!-- Rating -->
                    @if($product->reviews_count > 0)
                    <div class="mb-2">
                        <div class="d-flex align-items-center">
                            {!! $product->stars_html !!}
                            <span class="text-muted small ms-2">({{ $product->reviews_count }})</span>
                        </div>
                    </div>
                    @endif

                    <!-- Price -->
                    <div class="price-section mt-auto mb-3">
                        @if($product->sale_price < $product->price)
                        <div class="d-flex align-items-center gap-2">
                            <span class="h6 text-danger fw-bold mb-0">{{ number_format($product->sale_price) }}₫</span>
                            <span class="text-muted text-decoration-line-through small">{{ number_format($product->price) }}₫</span>
                        </div>
                        @else
                        <span class="h6 text-primary fw-bold">{{ number_format($product->price) }}₫</span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', $product) }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                        </a>
                        <button class="btn btn-primary btn-sm add-to-cart"
                                data-product-id="{{ $product->id }}">
                            <i class="fas fa-shopping-cart me-1"></i>Thêm vào giỏ
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($wishlists->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $wishlists->links() }}
    </div>
    @endif

    @else
    <!-- Empty Wishlist -->
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-heart text-muted" style="font-size: 4rem;"></i>
        </div>
        <h4 class="text-muted mb-3">Danh sách yêu thích trống</h4>
        <p class="text-muted mb-4">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="fas fa-shopping-bag me-2"></i>Khám phá sản phẩm
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Remove from wishlist
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-wishlist')) {
        e.preventDefault();
        const button = e.target.closest('.remove-wishlist');
        const productId = button.dataset.productId;

        if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
            fetch('{{ route("wishlist.remove") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the product card
                    button.closest('.col-lg-3').remove();

                    // Update wishlist count in header if exists
                    const wishlistBadge = document.querySelector('.wishlist-count');
                    if (wishlistBadge) {
                        wishlistBadge.textContent = data.wishlist_count;
                    }

                    // Show empty state if no more items
                    if (document.querySelectorAll('.product-card').length === 0) {
                        location.reload();
                    }

                    // Show success message
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra, vui lòng thử lại.', 'error');
            });
        }
    }
});

// Add to cart
document.addEventListener('click', function(e) {
    if (e.target.closest('.add-to-cart')) {
        e.preventDefault();
        const button = e.target.closest('.add-to-cart');
        const productId = button.dataset.productId;

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Đã thêm vào giỏ hàng!', 'success');

                // Update cart count if exists
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                }
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra, vui lòng thử lại.', 'error');
        });
    }
});

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}
</script>
@endpush
