@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin Panel')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <!-- Statistics Cards -->
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-primary me-3">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Tổng Users</h6>
                    <h4 class="mb-0">{{ number_format($stats['total_users']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-success me-3">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Tổng Sản phẩm</h6>
                    <h4 class="mb-0">{{ number_format($stats['total_products']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-warning me-3">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Tổng Đơn hàng</h6>
                    <h4 class="mb-0">{{ number_format($stats['total_orders']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-info me-3">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Tổng Doanh thu</h6>
                    <h4 class="mb-0">{{ number_format($stats['total_revenue']) }}đ</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Đơn hàng gần đây</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                @if($recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td><span class="text-success">{{ number_format($order->total) }}đ</span></td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Chờ xử lý</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info">Đang xử lý</span>
                                                @break
                                            @case('shipped')
                                                <span class="badge bg-primary">Đã giao</span>
                                                @break
                                            @case('delivered')
                                                <span class="badge bg-success">Hoàn thành</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có đơn hàng nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Sản phẩm sắp hết</h5>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-warning">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                @if($low_stock_products->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($low_stock_products as $product)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ Str::limit($product->name, 25) }}</h6>
                                <small class="text-muted">{{ $product->category->name }}</small>
                            </div>
                            <span class="badge {{ $product->stock <= 5 ? 'bg-danger' : 'bg-warning' }} rounded-pill">
                                {{ $product->stock }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">Tất cả sản phẩm đều có đủ tồn kho</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Thao tác nhanh</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i>Thêm Sản phẩm
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-folder-plus me-2"></i>Thêm Danh mục
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-warning w-100">
                            <i class="fas fa-clock me-2"></i>Đơn chờ xử lý
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-info w-100">
                            <i class="fas fa-user-plus me-2"></i>Thêm User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
