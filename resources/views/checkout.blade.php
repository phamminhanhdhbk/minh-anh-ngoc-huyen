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
                        @if (session('error'))
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
                                        <option value="An Giang" {{ old('city') == 'An Giang' ? 'selected' : '' }}>An Giang
                                        </option>
                                        <option value="Ba Ria - Vung Tau"
                                            {{ old('city') == 'Ba Ria - Vung Tau' ? 'selected' : '' }}>Bà Rịa - Vũng Tàu
                                        </option>
                                        <option value="Bac Giang" {{ old('city') == 'Bac Giang' ? 'selected' : '' }}>Bắc
                                            Giang</option>
                                        <option value="Bac Kan" {{ old('city') == 'Bac Kan' ? 'selected' : '' }}>Bắc Kạn
                                        </option>
                                        <option value="Bac Lieu" {{ old('city') == 'Bac Lieu' ? 'selected' : '' }}>Bạc Liêu
                                        </option>
                                        <option value="Bac Ninh" {{ old('city') == 'Bac Ninh' ? 'selected' : '' }}>Bắc Ninh
                                        </option>
                                        <option value="Ben Tre" {{ old('city') == 'Ben Tre' ? 'selected' : '' }}>Bến Tre
                                        </option>
                                        <option value="Binh Dinh" {{ old('city') == 'Binh Dinh' ? 'selected' : '' }}>Bình
                                            Định</option>
                                        <option value="Binh Duong" {{ old('city') == 'Binh Duong' ? 'selected' : '' }}>Bình
                                            Dương</option>
                                        <option value="Binh Phuoc" {{ old('city') == 'Binh Phuoc' ? 'selected' : '' }}>Bình
                                            Phước</option>
                                        <option value="Binh Thuan" {{ old('city') == 'Binh Thuan' ? 'selected' : '' }}>Bình
                                            Thuận</option>
                                        <option value="Ca Mau" {{ old('city') == 'Ca Mau' ? 'selected' : '' }}>Cà Mau
                                        </option>
                                        <option value="Can Tho" {{ old('city') == 'Can Tho' ? 'selected' : '' }}>Cần Thơ
                                        </option>
                                        <option value="Cao Bang" {{ old('city') == 'Cao Bang' ? 'selected' : '' }}>Cao Bằng
                                        </option>
                                        <option value="Da Nang" {{ old('city') == 'Da Nang' ? 'selected' : '' }}>Đà Nẵng
                                        </option>
                                        <option value="Dak Lak" {{ old('city') == 'Dak Lak' ? 'selected' : '' }}>Đắk Lắk
                                        </option>
                                        <option value="Dak Nong" {{ old('city') == 'Dak Nong' ? 'selected' : '' }}>Đắk Nông
                                        </option>
                                        <option value="Dien Bien" {{ old('city') == 'Dien Bien' ? 'selected' : '' }}>Điện
                                            Biên</option>
                                        <option value="Dong Nai" {{ old('city') == 'Dong Nai' ? 'selected' : '' }}>Đồng Nai
                                        </option>
                                        <option value="Dong Thap" {{ old('city') == 'Dong Thap' ? 'selected' : '' }}>Đồng
                                            Tháp</option>
                                        <option value="Gia Lai" {{ old('city') == 'Gia Lai' ? 'selected' : '' }}>Gia Lai
                                        </option>
                                        <option value="Ha Giang" {{ old('city') == 'Ha Giang' ? 'selected' : '' }}>Hà Giang
                                        </option>
                                        <option value="Ha Nam" {{ old('city') == 'Ha Nam' ? 'selected' : '' }}>Hà Nam
                                        </option>
                                        <option value="Ha Noi" {{ old('city') == 'Ha Noi' ? 'selected' : '' }}>Hà Nội
                                        </option>
                                        <option value="Ha Tinh" {{ old('city') == 'Ha Tinh' ? 'selected' : '' }}>Hà Tĩnh
                                        </option>
                                        <option value="Hai Duong" {{ old('city') == 'Hai Duong' ? 'selected' : '' }}>Hải
                                            Dương</option>
                                        <option value="Hai Phong" {{ old('city') == 'Hai Phong' ? 'selected' : '' }}>Hải
                                            Phòng</option>
                                        <option value="Hau Giang" {{ old('city') == 'Hau Giang' ? 'selected' : '' }}>Hậu
                                            Giang</option>
                                        <option value="Ho Chi Minh" {{ old('city') == 'Ho Chi Minh' ? 'selected' : '' }}>Hồ
                                            Chí Minh</option>
                                        <option value="Hoa Binh" {{ old('city') == 'Hoa Binh' ? 'selected' : '' }}>Hòa Bình
                                        </option>
                                        <option value="Hung Yen" {{ old('city') == 'Hung Yen' ? 'selected' : '' }}>Hưng Yên
                                        </option>
                                        <option value="Khanh Hoa" {{ old('city') == 'Khanh Hoa' ? 'selected' : '' }}>Khánh
                                            Hòa</option>
                                        <option value="Kien Giang" {{ old('city') == 'Kien Giang' ? 'selected' : '' }}>Kiên
                                            Giang</option>
                                        <option value="Kon Tum" {{ old('city') == 'Kon Tum' ? 'selected' : '' }}>Kon Tum
                                        </option>
                                        <option value="Lai Chau" {{ old('city') == 'Lai Chau' ? 'selected' : '' }}>Lai Châu
                                        </option>
                                        <option value="Lam Dong" {{ old('city') == 'Lam Dong' ? 'selected' : '' }}>Lâm Đồng
                                        </option>
                                        <option value="Lang Son" {{ old('city') == 'Lang Son' ? 'selected' : '' }}>Lạng Sơn
                                        </option>
                                        <option value="Lao Cai" {{ old('city') == 'Lao Cai' ? 'selected' : '' }}>Lào Cai
                                        </option>
                                        <option value="Long An" {{ old('city') == 'Long An' ? 'selected' : '' }}>Long An
                                        </option>
                                        <option value="Nam Dinh" {{ old('city') == 'Nam Dinh' ? 'selected' : '' }}>Nam Định
                                        </option>
                                        <option value="Nghe An" {{ old('city') == 'Nghe An' ? 'selected' : '' }}>Nghệ An
                                        </option>
                                        <option value="Ninh Binh" {{ old('city') == 'Ninh Binh' ? 'selected' : '' }}>Ninh
                                            Bình</option>
                                        <option value="Ninh Thuan" {{ old('city') == 'Ninh Thuan' ? 'selected' : '' }}>Ninh
                                            Thuận</option>
                                        <option value="Phu Tho" {{ old('city') == 'Phu Tho' ? 'selected' : '' }}>Phú Thọ
                                        </option>
                                        <option value="Phu Yen" {{ old('city') == 'Phu Yen' ? 'selected' : '' }}>Phú Yên
                                        </option>
                                        <option value="Quang Binh" {{ old('city') == 'Quang Binh' ? 'selected' : '' }}>
                                            Quảng Bình</option>
                                        <option value="Quang Nam" {{ old('city') == 'Quang Nam' ? 'selected' : '' }}>Quảng
                                            Nam</option>
                                        <option value="Quang Ngai" {{ old('city') == 'Quang Ngai' ? 'selected' : '' }}>
                                            Quảng Ngãi</option>
                                        <option value="Quang Ninh" {{ old('city') == 'Quang Ninh' ? 'selected' : '' }}>
                                            Quảng Ninh</option>
                                        <option value="Quang Tri" {{ old('city') == 'Quang Tri' ? 'selected' : '' }}>Quảng
                                            Trị</option>
                                        <option value="Soc Trang" {{ old('city') == 'Soc Trang' ? 'selected' : '' }}>Sóc
                                            Trăng</option>
                                        <option value="Son La" {{ old('city') == 'Son La' ? 'selected' : '' }}>Sơn La
                                        </option>
                                        <option value="Tay Ninh" {{ old('city') == 'Tay Ninh' ? 'selected' : '' }}>Tây Ninh
                                        </option>
                                        <option value="Thai Binh" {{ old('city') == 'Thai Binh' ? 'selected' : '' }}>Thái
                                            Bình</option>
                                        <option value="Thai Nguyen" {{ old('city') == 'Thai Nguyen' ? 'selected' : '' }}>
                                            Thái Nguyên</option>
                                        <option value="Thanh Hoa" {{ old('city') == 'Thanh Hoa' ? 'selected' : '' }}>Thanh
                                            Hóa</option>
                                        <option value="Thua Thien Hue"
                                            {{ old('city') == 'Thua Thien Hue' ? 'selected' : '' }}>Thừa Thiên Huế</option>
                                        <option value="Tien Giang" {{ old('city') == 'Tien Giang' ? 'selected' : '' }}>Tiền
                                            Giang</option>
                                    </select>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Địa chỉ *</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3" required
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
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                        value="cod" {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cod">
                                        <i class="fas fa-money-bill-wave me-2"></i>
                                        Thanh toán khi nhận hàng (COD)
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method"
                                        id="bank_transfer" value="bank_transfer"
                                        {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bank_transfer">
                                        <i class="fas fa-university me-2"></i>
                                        Chuyển khoản ngân hàng
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="online"
                                        value="online" {{ old('payment_method') == 'online' ? 'checked' : '' }}>
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
                        @if ($cartItems->count() > 0)
                            @foreach ($cartItems as $item)
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
                                    <span>
                                        @if ($shipping == 0)
                                            <strong class="text-success">Miễn phí vận chuyển</strong>
                                        @else
                                            {{ number_format($shipping) }}₫
                                        @endif
                                    </span>
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
