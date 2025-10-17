@extends('admin.layouts.app')

@section('title', 'Chi tiết Đơn hàng #' . $order->id . ' - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-shopping-bag me-2"></i>Đơn hàng #{{ $order->id }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
            <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>In đơn hàng
            </button>
        </div>

        <!-- Status update buttons -->
        @if($order->status == 'pending')
        <div class="btn-group">
            <button type="button" class="btn btn-info" onclick="updateStatus('processing')">
                <i class="fas fa-cog me-2"></i>Xử lý đơn hàng
            </button>
            <button type="button" class="btn btn-danger" onclick="updateStatus('cancelled')">
                <i class="fas fa-times me-2"></i>Hủy đơn hàng
            </button>
        </div>
        @elseif($order->status == 'processing')
        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="updateStatus('shipped')">
                <i class="fas fa-shipping-fast me-2"></i>Gửi hàng
            </button>
            <button type="button" class="btn btn-danger" onclick="updateStatus('cancelled')">
                <i class="fas fa-times me-2"></i>Hủy đơn hàng
            </button>
        </div>
        @elseif($order->status == 'shipped')
        <div class="btn-group">
            <button type="button" class="btn btn-success" onclick="updateStatus('delivered')">
                <i class="fas fa-check me-2"></i>Đã giao hàng
            </button>
        </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Sản phẩm trong đơn hàng</h5>
                @php
                    $statusColors = [
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    ];
                    $statusLabels = [
                        'pending' => 'Chờ xử lý',
                        'processing' => 'Đang xử lý',
                        'shipped' => 'Đã gửi',
                        'delivered' => 'Đã giao',
                        'cancelled' => 'Đã hủy'
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6">
                    {{ $statusLabels[$order->status] ?? $order->status }}
                </span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product_name }}"
                                                 class="me-3 rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="me-3 bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                                            @if($item->product)
                                                <small class="text-muted">SKU: {{ $item->product->sku ?: 'N/A' }}</small>
                                            @else
                                                <small class="text-danger">Sản phẩm đã bị xóa</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($item->product_price) }}₫</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                </td>
                                <td>
                                    <strong>{{ number_format($item->product_price * $item->quantity) }}₫</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <th colspan="3" class="text-end">Tổng cộng:</th>
                                <th>
                                    <span class="fs-5 text-success">{{ number_format($order->total) }}₫</span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Lịch sử đơn hàng</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-success">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Đơn hàng được tạo</h6>
                            <p class="text-muted mb-0">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>

                    @if($order->status != 'pending')
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-info">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Đang xử lý</h6>
                            <p class="text-muted mb-0">{{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    @endif

                    @if(in_array($order->status, ['shipped', 'delivered']))
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-primary">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Đã gửi hàng</h6>
                            <p class="text-muted mb-0">{{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($order->status == 'delivered')
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Đã giao hàng</h6>
                            <p class="text-muted mb-0">{{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    @elseif($order->status == 'cancelled')
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-danger">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Đã hủy</h6>
                            <p class="text-muted mb-0">{{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thông tin khách hàng</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width: 40px; height: 40px;">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $order->customer_name }}</h6>
                        <small class="text-muted">
                            @if($order->user)
                                Thành viên
                            @else
                                Khách
                            @endif
                        </small>
                    </div>
                </div>

                <hr>

                <div class="mb-2">
                    <i class="fas fa-envelope text-muted me-2"></i>
                    <a href="mailto:{{ $order->customer_email }}" class="text-decoration-none">
                        {{ $order->customer_email }}
                    </a>
                </div>

                <div class="mb-2">
                    <i class="fas fa-phone text-muted me-2"></i>
                    {{ $order->customer_phone }}
                </div>

                @if($order->user)
                <div class="mb-2">
                    <i class="fas fa-calendar text-muted me-2"></i>
                    Đăng ký: {{ $order->user->created_at->format('d/m/Y') }}
                </div>

                <div class="mb-2">
                    <i class="fas fa-shopping-bag text-muted me-2"></i>
                    Tổng đơn hàng: {{ $order->user->orders->count() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thông tin đơn hàng</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Mã đơn hàng:</span>
                    <strong>#{{ $order->id }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Ngày đặt:</span>
                    <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Số sản phẩm:</span>
                    <span>{{ $order->orderItems->sum('quantity') }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Phương thức thanh toán:</span>
                    <span class="badge bg-info">COD</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <strong>Tổng cộng:</strong>
                    <strong class="text-success fs-5">{{ number_format($order->total) }}₫</strong>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        @if(in_array($order->status, ['pending', 'processing']))
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Thao tác nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($order->status == 'pending')
                    <button type="button" class="btn btn-info" onclick="updateStatus('processing')">
                        <i class="fas fa-cog me-2"></i>Bắt đầu xử lý
                    </button>
                    @endif

                    @if($order->status == 'processing')
                    <button type="button" class="btn btn-primary" onclick="updateStatus('shipped')">
                        <i class="fas fa-shipping-fast me-2"></i>Gửi hàng
                    </button>
                    @endif

                    @if($order->status == 'shipped')
                    <button type="button" class="btn btn-success" onclick="updateStatus('delivered')">
                        <i class="fas fa-check me-2"></i>Đánh dấu đã giao
                    </button>
                    @endif

                    <button type="button" class="btn btn-outline-danger" onclick="updateStatus('cancelled')">
                        <i class="fas fa-times me-2"></i>Hủy đơn hàng
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Status Update Form -->
<form id="statusForm" action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" id="statusInput">
</form>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    height: 100%;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
}

.timeline-item.active .timeline-marker {
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content h6 {
    margin-bottom: 2px;
    color: #495057;
}

@media print {
    .btn-toolbar, .card-header .badge {
        display: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function updateStatus(status) {
    const statusLabels = {
        'processing': 'Đang xử lý',
        'shipped': 'Đã gửi hàng',
        'delivered': 'Đã giao hàng',
        'cancelled': 'Đã hủy'
    };

    let message = `Bạn có chắc muốn chuyển trạng thái đơn hàng #{{ $order->id }} thành "${statusLabels[status]}"?`;

    if (status === 'cancelled') {
        message += '\n\nLưu ý: Hành động này sẽ không thể hoàn tác!';
    }

    if (confirm(message)) {
        document.getElementById('statusInput').value = status;
        document.getElementById('statusForm').submit();
    }
}
</script>
@endpush
