@extends('layouts.app')

@section('title', 'Thanh toán - Cửa hàng')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Thông tin thanh toán
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                        @csrf

                        <!-- Thông tin giao hàng -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3">Thông tin giao hàng</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thành phố/Tỉnh *</label>
                                <select class="form-select @error('city') is-invalid @enderror" name="city" required>
                                    <option value="">Chọn thành phố/tỉnh</option>
                                    <option value="Ho Chi Minh" {{ old('city') == 'Ho Chi Minh' ? 'selected' : '' }}>Hồ Chí Minh</option>
                                    <option value="Ha Noi" {{ old('city') == 'Ha Noi' ? 'selected' : '' }}>Hà Nội</option>
                                    <option value="Da Nang" {{ old('city') == 'Da Nang' ? 'selected' : '' }}>Đà Nẵng</option>
                                    <option value="Can Tho" {{ old('city') == 'Can Tho' ? 'selected' : '' }}>Cần Thơ</option>
                                    <option value="Hai Phong" {{ old('city') == 'Hai Phong' ? 'selected' : '' }}>Hải Phòng</option>
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Địa chỉ *</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          name="address" rows="3" required
                                          placeholder="Số nhà, tên đường, phường/xã, quận/huyện">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Ghi chú đơn hàng</label>
                                <textarea class="form-control" name="notes" rows="2"
                                          placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn.">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Phương thức thanh toán -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Phương thức thanh toán</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method"
                                       id="cod" value="cod" {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cod">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method"
                                       id="bank_transfer" value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                <label class="form-check-label" for="bank_transfer">
                                    <i class="fas fa-university me-2"></i>
                                    Chuyển khoản ngân hàng
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method"
                                       id="online" value="online" {{ old('payment_method') == 'online' ? 'checked' : '' }}>
                                <label class="form-check-label" for="online">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Thanh toán online (Visa/MasterCard)
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Đặt hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>Đơn hàng của bạn
                    </h6>
                </div>
                <div class="card-body">
                    @if($cartItems->count() > 0)
                        @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                <small class="text-muted">Số lượng: {{ $item->quantity }}</small>
                            </div>
                            <div class="text-end">
                                <strong>{{ number_format(($item->product->sale_price ?: $item->product->price) * $item->quantity) }}₫</strong>
                            </div>
                        </div>
                        @endforeach

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($subtotal) }}₫</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span>{{ number_format($shipping) }}₫</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Tổng cộng:</strong>
                                <strong class="text-primary">{{ number_format($total) }}₫</strong>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Giỏ hàng trống</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Security Info -->
            <div class="card mt-4">
                <div class="card-body text-center">
                    <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                    <h6>Thanh toán an toàn</h6>
                    <small class="text-muted">
                        Thông tin của bạn được bảo mật bằng SSL 256-bit
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation and debug
    $('#checkout-form').on('submit', function(e) {
        console.log('Form submitted!');

        let isValid = true;
        let formData = {};

        // Collect form data
        $(this).find('input, select, textarea').each(function() {
            formData[$(this).attr('name')] = $(this).val();
        });

        console.log('Form data:', formData);

        // Check required fields
        $(this).find('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
                console.log('Missing required field:', $(this).attr('name'));
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Check payment method
        if (!$('input[name="payment_method"]:checked').length) {
            isValid = false;
            console.log('No payment method selected');
            alert('Vui lòng chọn phương thức thanh toán');
        }

        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc');
            return false;
        }

        // Add loading state
        $(this).find('button[type="submit"]').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...'
        );

        return true;
    });

    // Phone number formatting
    $('input[name="phone"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 10) {
            value = value.substr(0, 10);
        }
        $(this).val(value);
    });
});
</script>
@endpush
