@php
$inWishlist = auth()->check() ? auth()->user()->wishlists()->where('product_id', $product->id)->exists() : false;
@endphp

<button class="btn {{ $inWishlist ? 'btn-danger' : 'btn-outline-danger' }} wishlist-btn {{ $class ?? '' }}"
        data-product-id="{{ $product->id }}"
        data-in-wishlist="{{ $inWishlist ? 'true' : 'false' }}"
        title="{{ $inWishlist ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
        @guest
        onclick="alert('Vui lòng đăng nhập để sử dụng tính năng này.'); return false;"
        @endguest>
    <i class="fas fa-heart me-1"></i>
    <span class="wishlist-text">{{ $inWishlist ? 'Đã yêu thích' : 'Yêu thích' }}</span>
</button>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wishlist toggle functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.wishlist-btn') && !e.target.closest('.wishlist-btn').hasAttribute('onclick')) {
            e.preventDefault();
            const button = e.target.closest('.wishlist-btn');
            const productId = button.dataset.productId;
            const originalText = button.querySelector('.wishlist-text').textContent;
            const originalClass = button.className;

            // Disable button during request
            button.disabled = true;
            button.querySelector('.wishlist-text').textContent = 'Đang xử lý...';

            fetch('{{ route("wishlist.toggle") }}', {
                method: 'POST',
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
                    // Update button state
                    button.dataset.inWishlist = data.in_wishlist;

                    if (data.in_wishlist) {
                        button.className = button.className.replace('btn-outline-danger', 'btn-danger');
                        button.querySelector('.wishlist-text').textContent = 'Đã yêu thích';
                        button.title = 'Xóa khỏi yêu thích';
                    } else {
                        button.className = button.className.replace('btn-danger', 'btn-outline-danger');
                        button.querySelector('.wishlist-text').textContent = 'Yêu thích';
                        button.title = 'Thêm vào yêu thích';
                    }

                    // Update wishlist count in header if exists
                    const wishlistCount = document.querySelector('.wishlist-count');
                    if (wishlistCount) {
                        wishlistCount.textContent = data.wishlist_count;
                    }

                    // Show notification
                    showWishlistNotification(data.message, 'success');
                } else {
                    showWishlistNotification(data.message, 'error');

                    // Restore original state
                    button.className = originalClass;
                    button.querySelector('.wishlist-text').textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showWishlistNotification('Có lỗi xảy ra, vui lòng thử lại.', 'error');

                // Restore original state
                button.className = originalClass;
                button.querySelector('.wishlist-text').textContent = originalText;
            })
            .finally(() => {
                button.disabled = false;
            });
        }
    });
});

function showWishlistNotification(message, type) {
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
