@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">
                <i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn
            </h2>

            @if($cartItems->count() > 0)
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Sản phẩm trong giỏ hàng</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Tổng tiền</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cartItems as $item)
                                        <tr id="cart-item-{{ $item->id }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->product->image ?? 'https://via.placeholder.com/60x60' }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="rounded me-3"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                        <small class="text-muted">{{ $item->product->category->name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary">{{ number_format($item->product->price) }}đ</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <span class="mx-3 fw-bold">{{ $item->quantity }}</span>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">{{ number_format($item->product->price * $item->quantity) }}đ</span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFromCart({{ $item->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Tổng kết đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tổng sản phẩm:</span>
                                <span>{{ $cartItems->sum('quantity') }} món</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($total) }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span>Miễn phí</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Tổng cộng:</strong>
                                <strong class="text-primary">{{ number_format($total) }}đ</strong>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card me-2"></i>Thanh toán
                                </a>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Coupon Section -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Mã giảm giá</h6>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Nhập mã giảm giá" id="coupon-code">
                                <button class="btn btn-outline-primary" type="button" onclick="applyCoupon()">
                                    Áp dụng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Empty Cart -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-shopping-cart fa-5x text-muted"></i>
                </div>
                <h4 class="text-muted mb-3">Giỏ hàng của bạn đang trống</h4>
                <p class="text-muted mb-4">Hãy thêm một số sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i>Bắt đầu mua sắm
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2">Đang xử lý...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateQuantity(cartId, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(cartId);
        return;
    }

    showLoading();

    $.ajax({
        url: `/cart/${cartId}`,
        method: 'PUT',
        data: {
            quantity: newQuantity,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.message || 'Có lỗi xảy ra');
            }
        },
        error: function(xhr) {
            alert('Có lỗi xảy ra, vui lòng thử lại');
        },
        complete: function() {
            hideLoading();
        }
    });
}

function removeFromCart(cartId) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        return;
    }

    showLoading();

    $.ajax({
        url: `/cart/${cartId}`,
        method: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                $(`#cart-item-${cartId}`).fadeOut(300, function() {
                    location.reload();
                });
            } else {
                alert(response.message || 'Có lỗi xảy ra');
            }
        },
        error: function(xhr) {
            alert('Có lỗi xảy ra, vui lòng thử lại');
        },
        complete: function() {
            hideLoading();
        }
    });
}

function applyCoupon() {
    const code = $('#coupon-code').val().trim();
    if (!code) {
        alert('Vui lòng nhập mã giảm giá');
        return;
    }

    showLoading();

    $.ajax({
        url: '/cart/apply-coupon',
        method: 'POST',
        data: {
            code: code,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.message || 'Mã giảm giá không hợp lệ');
            }
        },
        error: function(xhr) {
            alert('Mã giảm giá không hợp lệ');
        },
        complete: function() {
            hideLoading();
        }
    });
}

function showLoading() {
    $('#loadingModal').modal('show');
}

function hideLoading() {
    $('#loadingModal').modal('hide');
}

// Auto-hide alerts
$(document).ready(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
@endpush

@push('styles')
<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.text-primary {
    color: #0d6efd !important;
}

.text-success {
    color: #198754 !important;
}

.modal-content {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>
@endpush
