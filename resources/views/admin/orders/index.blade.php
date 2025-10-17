@extends('admin.layouts.app')

@section('title', 'Quản lý Đơn hàng - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-shopping-bag me-2"></i>Quản lý Đơn hàng
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>In báo cáo
            </button>
            <button type="button" class="btn btn-outline-success" onclick="exportOrders()">
                <i class="fas fa-file-excel me-2"></i>Xuất Excel
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2 stat-card cursor-pointer" onclick="filterOrders('all')">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng đơn hàng</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2 stat-card cursor-pointer" onclick="filterOrders('pending')">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Chờ xử lý</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['pending']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-info shadow h-100 py-2 stat-card cursor-pointer" onclick="filterOrders('processing')">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Đang xử lý</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['processing']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cog fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2 stat-card cursor-pointer" onclick="filterOrders('shipped')">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Đã gửi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['shipped']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shipping-fast fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2 stat-card cursor-pointer" onclick="filterOrders('delivered')">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đã giao</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['delivered']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-danger shadow h-100 py-2 stat-card cursor-pointer" onclick="filterOrders('cancelled')">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Đã hủy</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['cancelled']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Doanh thu</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_revenue']) }}₫</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Tìm kiếm</label>
                <input type="text" class="form-control" id="search" name="search"
                       value="{{ request('search') }}" placeholder="Mã đơn hàng, tên khách hàng...">
            </div>

            <div class="col-md-2">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tất cả</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đã gửi</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="date_from" class="form-label">Từ ngày</label>
                <input type="date" class="form-control" id="date_from" name="date_from"
                       value="{{ request('date_from') }}">
            </div>

            <div class="col-md-2">
                <label for="date_to" class="form-label">Đến ngày</label>
                <input type="date" class="form-control" id="date_to" name="date_to"
                       value="{{ request('date_to') }}">
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-2"></i>Tìm kiếm
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-refresh me-2"></i>Đặt lại
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Danh sách đơn hàng ({{ $orders->total() }} kết quả)</h5>
    </div>
    <div class="card-body">
        @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-bold">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $order->customer_name }}</strong>
                                <small class="text-muted d-block">{{ $order->customer_email }}</small>
                                @if($order->user)
                                    <small class="badge bg-info">Thành viên</small>
                                @else
                                    <small class="badge bg-secondary">Khách</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                {{ $order->orderItems->sum('quantity') }} sản phẩm
                            </span>
                        </td>
                        <td>
                            <strong class="text-success">{{ number_format($order->total) }}₫</strong>
                        </td>
                        <td>
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
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td>
                            <div>
                                {{ $order->created_at->format('d/m/Y') }}
                                <small class="text-muted d-block">{{ $order->created_at->format('H:i') }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="btn btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($order->status == 'pending')
                                <button type="button" class="btn btn-outline-info"
                                        onclick="updateStatus({{ $order->id }}, 'processing')"
                                        title="Xử lý đơn hàng">
                                    <i class="fas fa-cog"></i>
                                </button>
                                @endif

                                @if($order->status == 'processing')
                                <button type="button" class="btn btn-outline-primary"
                                        onclick="updateStatus({{ $order->id }}, 'shipped')"
                                        title="Gửi hàng">
                                    <i class="fas fa-shipping-fast"></i>
                                </button>
                                @endif

                                @if($order->status == 'shipped')
                                <button type="button" class="btn btn-outline-success"
                                        onclick="updateStatus({{ $order->id }}, 'delivered')"
                                        title="Đánh dấu đã giao">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif

                                @if(in_array($order->status, ['pending', 'processing', 'shipped']))
                                <button type="button" class="btn btn-outline-danger"
                                        onclick="updateStatus({{ $order->id }}, 'cancelled')"
                                        title="Hủy đơn hàng">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Không có đơn hàng nào</h5>
            <p class="text-muted">Chưa có đơn hàng nào được tạo trong hệ thống.</p>
        </div>
        @endif
    </div>
</div>

<!-- Status Update Form -->
<form id="statusForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" id="statusInput">
</form>
@endsection

@push('styles')
<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
.border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
.border-left-dark { border-left: 0.25rem solid #5a5c69 !important; }

.stat-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.stat-card.active {
    border: 2px solid #007bff;
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.cursor-pointer {
    cursor: pointer;
}
</style>
@endpush

@push('scripts')
<script>
function filterOrders(status) {
    // Remove active class from all cards
    document.querySelectorAll('.stat-card').forEach(card => {
        card.classList.remove('active');
    });

    // Add active class to clicked card
    event.currentTarget.classList.add('active');

    // Get current URL and params
    const url = new URL(window.location);
    const params = new URLSearchParams(url.search);

    // Clear existing status filter
    params.delete('status');

    // Apply filter based on status
    if (status !== 'all') {
        params.set('status', status);
    }

    // Reset to first page
    params.delete('page');

    // Navigate to filtered URL
    url.search = params.toString();
    window.location.href = url.toString();
}

function updateStatus(orderId, status) {
    const statusLabels = {
        'pending': 'Chờ xử lý',
        'processing': 'Đang xử lý',
        'shipped': 'Đã gửi',
        'delivered': 'Đã giao',
        'cancelled': 'Đã hủy'
    };

    if (confirm(`Bạn có chắc muốn chuyển trạng thái đơn hàng #${orderId} thành "${statusLabels[status]}"?`)) {
        const form = document.getElementById('statusForm');
        form.action = `/admin/orders/${orderId}/status`;
        document.getElementById('statusInput').value = status;
        form.submit();
    }
}

function exportOrders() {
    // Get current filters
    const url = new URL(window.location);
    const params = new URLSearchParams(url.search);

    // Create export URL with current filters
    const exportUrl = new URL('/admin/orders/export', window.location.origin);
    exportUrl.search = params.toString();

    // Download the file
    window.location.href = exportUrl.toString();
}

// Highlight active filter on page load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    // Determine which card should be active
    let activeIndex = 0; // Default to "all" (first card)

    const statusMap = {
        'pending': 1,
        'processing': 2,
        'shipped': 3,
        'delivered': 4,
        'cancelled': 5
    };

    if (status && statusMap[status] !== undefined) {
        activeIndex = statusMap[status];
    }

    // Add active class to the appropriate card
    const cards = document.querySelectorAll('.stat-card');
    if (cards[activeIndex]) {
        cards[activeIndex].classList.add('active');
    }
});
</script>
@endpush
