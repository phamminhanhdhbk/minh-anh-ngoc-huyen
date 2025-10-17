@extends('layouts.app')

@section('title', 'Đặt hàng thành công - Cửa hàng')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>

                    <h2 class="text-success mb-3">Đặt hàng thành công!</h2>
                    <p class="lead mb-4">
                        Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.
                    </p>

                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Thông tin đơn hàng</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                                    <p><strong>Trạng thái:</strong>
                                        <span class="badge bg-warning">{{ ucfirst($order->status) }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Tổng tiền:</strong>
                                        <span class="text-primary fs-5">{{ number_format($order->total) }}₫</span>
                                    </p>
                                    <p><strong>Phương thức thanh toán:</strong>
                                        @switch($order->payment_method)
                                            @case('cod')
                                                Thanh toán khi nhận hàng
                                                @break
                                            @case('bank_transfer')
                                                Chuyển khoản ngân hàng
                                                @break
                                            @case('online')
                                                Thanh toán online
                                                @break
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Chi tiết đơn hàng</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>{{ $item->product_name }}</td>
                                            <td>{{ number_format($item->product_price) }}₫</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->total) }}₫</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Tạm tính:</th>
                                            <th>{{ number_format($order->subtotal) }}₫</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Phí vận chuyển:</th>
                                            <th>{{ number_format($order->shipping) }}₫</th>
                                        </tr>
                                        <tr class="table-primary">
                                            <th colspan="3">Tổng cộng:</th>
                                            <th>{{ number_format($order->total) }}₫</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-info text-white mb-4">
                        <div class="card-body">
                            <h6 class="card-title">Thông tin giao hàng</h6>
                            <p class="mb-1"><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                            <p class="mb-1"><strong>Điện thoại:</strong> {{ $order->customer_phone }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                            <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>
                            @if($order->notes)
                                <p class="mb-0 mt-2"><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                            @endif
                        </div>
                    </div>

                    @if($order->payment_method == 'bank_transfer')
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">Thông tin chuyển khoản</h6>
                        <p class="mb-2">Vui lòng chuyển khoản theo thông tin sau:</p>
                        <p class="mb-1"><strong>Ngân hàng:</strong> Vietcombank</p>
                        <p class="mb-1"><strong>Số tài khoản:</strong> 1234567890</p>
                        <p class="mb-1"><strong>Chủ tài khoản:</strong> Cửa hàng ABC</p>
                        <p class="mb-1"><strong>Số tiền:</strong> {{ number_format($order->total) }}₫</p>
                        <p class="mb-0"><strong>Nội dung:</strong> {{ $order->order_number }}</p>
                    </div>
                    @endif

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i>Về trang chủ
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
