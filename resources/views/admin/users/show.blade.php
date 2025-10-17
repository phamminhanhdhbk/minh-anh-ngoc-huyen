@extends('admin.layouts.app')

@section('title', 'Chi tiết Người dùng - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user me-2"></i>{{ $user->name }}
        @if($user->is_admin)
            <span class="badge bg-danger ms-2">Admin</span>
        @endif
        @if($user->id === auth()->id())
            <span class="badge bg-info ms-2">Bạn</span>
        @endif
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>Sửa
            </a>
        </div>

        @if($user->id !== auth()->id())
        <div class="btn-group">
            @if(!$user->is_admin)
                <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success"
                            onclick="return confirm('Cấp quyền admin cho {{ $user->name }}?')">
                        <i class="fas fa-user-shield me-2"></i>Cấp Admin
                    </button>
                </form>
            @else
                <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning"
                            onclick="return confirm('Gỡ quyền admin của {{ $user->name }}?')">
                        <i class="fas fa-user-minus me-2"></i>Gỡ Admin
                    </button>
                </form>
            @endif

            @if(!$user->orders()->exists())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Xóa người dùng {{ $user->name }}?\n\nHành động này không thể hoàn tác!')">
                        <i class="fas fa-trash me-2"></i>Xóa
                    </button>
                </form>
            @endif
        </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- User Profile -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>

                        @if($user->is_admin)
                            <div class="badge bg-danger mb-2 d-block">
                                <i class="fas fa-user-shield me-1"></i>Quản trị viên
                            </div>
                        @else
                            <div class="badge bg-secondary mb-2 d-block">
                                <i class="fas fa-user me-1"></i>Khách hàng
                            </div>
                        @endif

                        @if($user->email_verified_at)
                            <div class="badge bg-success d-block">
                                <i class="fas fa-check-circle me-1"></i>Email đã xác thực
                            </div>
                        @else
                            <div class="badge bg-warning d-block">
                                <i class="fas fa-exclamation-circle me-1"></i>Email chưa xác thực
                            </div>
                        @endif
                    </div>

                    <div class="col-md-9">
                        <h4 class="mb-3">{{ $user->name }}</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <p><i class="fas fa-envelope text-muted me-2"></i>
                                   <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                </p>
                                <p><i class="fas fa-calendar text-muted me-2"></i>
                                   Đăng ký: {{ $user->created_at->format('d/m/Y H:i') }}
                                </p>
                                <p><i class="fas fa-clock text-muted me-2"></i>
                                   Hoạt động cuối: {{ $user->updated_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p><i class="fas fa-id-card text-muted me-2"></i>
                                   ID: #{{ $user->id }}
                                </p>
                                @if($user->email_verified_at)
                                <p><i class="fas fa-check-circle text-muted me-2"></i>
                                   Xác thực: {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History -->
        @if($user->orders()->exists())
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lịch sử đơn hàng</h5>
                <span class="badge bg-primary">{{ $userStats['total_orders'] }} đơn hàng</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Sản phẩm</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders()->latest()->take(10)->get() as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-bold">
                                        #{{ $order->id }}
                                    </a>
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
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($user->orders()->count() > 10)
                <div class="text-center mt-3">
                    <small class="text-muted">Chỉ hiển thị 10 đơn hàng gần nhất</small>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Cart Items -->
        @if($user->carts()->exists())
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Giỏ hàng hiện tại</h5>
                <span class="badge bg-warning">{{ $userStats['cart_items'] }} sản phẩm</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Ngày thêm</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->carts as $cart)
                            <tr>
                                <td>
                                    @if($cart->product)
                                        <a href="{{ route('admin.products.show', $cart->product) }}" class="text-decoration-none">
                                            {{ $cart->product->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Sản phẩm đã bị xóa</span>
                                    @endif
                                </td>
                                <td>{{ $cart->quantity }}</td>
                                <td>{{ $cart->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thống kê hoạt động</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <div class="h4 text-primary">{{ $userStats['total_orders'] }}</div>
                        <small class="text-muted">Tổng đơn hàng</small>
                    </div>
                    <div class="col-6">
                        <div class="h4 text-success">{{ number_format($userStats['total_spent']) }}₫</div>
                        <small class="text-muted">Tổng chi tiêu</small>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-2">
                    <span>Chờ xử lý:</span>
                    <span class="badge bg-warning">{{ $userStats['pending_orders'] }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Hoàn thành:</span>
                    <span class="badge bg-success">{{ $userStats['completed_orders'] }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Đã hủy:</span>
                    <span class="badge bg-danger">{{ $userStats['cancelled_orders'] }}</span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Trong giỏ hàng:</span>
                    <span class="badge bg-info">{{ $userStats['cart_items'] }}</span>
                </div>
            </div>
        </div>

        <!-- Customer Level -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Phân loại khách hàng</h6>
            </div>
            <div class="card-body text-center">
                @php
                    $totalSpent = $userStats['total_spent'];
                    $level = 'Mới';
                    $levelColor = 'secondary';
                    $levelIcon = 'user';

                    if ($totalSpent >= 10000000) {
                        $level = 'VIP';
                        $levelColor = 'warning';
                        $levelIcon = 'crown';
                    } elseif ($totalSpent >= 5000000) {
                        $level = 'Thân thiết';
                        $levelColor = 'info';
                        $levelIcon = 'star';
                    } elseif ($totalSpent >= 1000000) {
                        $level = 'Bạc';
                        $levelColor = 'secondary';
                        $levelIcon = 'medal';
                    } elseif ($totalSpent > 0) {
                        $level = 'Đồng';
                        $levelColor = 'success';
                        $levelIcon = 'certificate';
                    }
                @endphp

                <div class="mb-3">
                    <i class="fas fa-{{ $levelIcon }} fa-3x text-{{ $levelColor }}"></i>
                </div>

                <h5 class="text-{{ $levelColor }}">{{ $level }}</h5>

                <div class="progress mt-3" style="height: 10px;">
                    @php
                        $progressPercent = min(100, ($totalSpent / 10000000) * 100);
                    @endphp
                    <div class="progress-bar bg-{{ $levelColor }}" style="width: {{ $progressPercent }}%"></div>
                </div>

                <small class="text-muted mt-2 d-block">
                    @if($totalSpent < 10000000)
                        Còn {{ number_format(10000000 - $totalSpent) }}₫ để đạt VIP
                    @else
                        Đã đạt cấp độ cao nhất
                    @endif
                </small>
            </div>
        </div>

        <!-- Quick Actions -->
        @if($user->id !== auth()->id())
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Thao tác nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(!$user->is_admin)
                        <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100"
                                    onclick="return confirm('Cấp quyền admin cho {{ $user->name }}?')">
                                <i class="fas fa-user-shield me-2"></i>Cấp quyền Admin
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning w-100"
                                    onclick="return confirm('Gỡ quyền admin của {{ $user->name }}?')">
                                <i class="fas fa-user-minus me-2"></i>Gỡ quyền Admin
                            </button>
                        </form>
                    @endif

                    <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-envelope me-2"></i>Gửi Email
                    </a>

                    @if(!$user->orders()->exists())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Xóa người dùng {{ $user->name }}?\n\nHành động này không thể hoàn tác!')">
                                <i class="fas fa-trash me-2"></i>Xóa người dùng
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info py-2 mb-0">
                            <small><i class="fas fa-info-circle me-1"></i> Không thể xóa người dùng có đơn hàng</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-user-circle fa-3x text-muted mb-3"></i>
                <h6>Đây là tài khoản của bạn</h6>
                <p class="text-muted">Bạn không thể thực hiện các thao tác quản lý trên chính tài khoản của mình.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
