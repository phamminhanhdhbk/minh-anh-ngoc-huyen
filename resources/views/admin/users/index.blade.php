@extends('admin.layouts.app')

@section('title', 'Quản lý Người dùng - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users me-2"></i>Quản lý Người dùng
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm người dùng
            </a>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-secondary" onclick="exportUsers()">
                <i class="fas fa-file-excel me-2"></i>Xuất Excel
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2 stat-card cursor-pointer" onclick="filterUsers('all')">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng người dùng</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2 stat-card cursor-pointer" onclick="filterUsers('admin')">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Admin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['admins']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-info shadow h-100 py-2 stat-card cursor-pointer" onclick="filterUsers('user')">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Khách hàng</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['regular_users']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2 stat-card cursor-pointer" onclick="filterUsers('recent')">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Đăng ký gần đây</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['recent_users']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-left-dark shadow h-100 py-2 stat-card cursor-pointer" onclick="filterUsers('customers')">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Đã mua hàng</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['active_customers']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
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
                       value="{{ request('search') }}" placeholder="Tên, email...">
            </div>

            <div class="col-md-2">
                <label for="role" class="form-label">Vai trò</label>
                <select class="form-select" id="role" name="role">
                    <option value="">Tất cả</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Khách hàng</option>
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
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-refresh me-2"></i>Đặt lại
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Danh sách người dùng ({{ $users->total() }} kết quả)</h5>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên & Email</th>
                        <th>Vai trò</th>
                        <th>Đơn hàng</th>
                        <th>Ngày đăng ký</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <strong class="text-primary">#{{ $user->id }}</strong>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $user->name }}</strong>
                                <small class="text-muted d-block">{{ $user->email }}</small>
                                @if($user->id === auth()->id())
                                    <small class="badge bg-info">Bạn</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($user->is_admin)
                                <span class="badge bg-danger">
                                    <i class="fas fa-user-shield me-1"></i>Admin
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-user me-1"></i>Khách hàng
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->orders_count > 0)
                                <a href="{{ route('admin.users.show', $user) }}" class="text-decoration-none">
                                    <span class="badge bg-primary">{{ $user->orders_count }} đơn hàng</span>
                                </a>
                            @else
                                <span class="badge bg-light text-dark">Chưa có</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                {{ $user->created_at->format('d/m/Y') }}
                                <small class="text-muted d-block">{{ $user->created_at->format('H:i') }}</small>
                            </div>
                        </td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Đã xác thực</span>
                            @else
                                <span class="badge bg-warning">Chưa xác thực</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="btn btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-outline-secondary" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if($user->id !== auth()->id())
                                    @if($user->is_admin)
                                        <button type="button" class="btn btn-outline-warning"
                                                onclick="toggleAdmin({{ $user->id }}, false)"
                                                title="Gỡ quyền admin">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-success"
                                                onclick="toggleAdmin({{ $user->id }}, true)"
                                                title="Cấp quyền admin">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    @endif

                                    @if(!$user->orders()->exists())
                                        <button type="button" class="btn btn-outline-danger"
                                                onclick="deleteUser({{ $user->id }})"
                                                title="Xóa người dùng">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
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
            {{ $users->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Không tìm thấy người dùng</h5>
            <p class="text-muted">Thử điều chỉnh bộ lọc để tìm thấy người dùng bạn cần.</p>
        </div>
        @endif
    </div>
</div>

<!-- Hidden Forms for Actions -->
<form id="toggleAdminForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="deleteUserForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
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
function filterUsers(type) {
    // Remove active class from all cards
    document.querySelectorAll('.stat-card').forEach(card => {
        card.classList.remove('active');
    });

    // Add active class to clicked card
    event.currentTarget.classList.add('active');

    // Get current URL and params
    const url = new URL(window.location);
    const params = new URLSearchParams(url.search);

    // Clear existing filters
    params.delete('role');
    params.delete('filter');
    params.delete('date_from');
    params.delete('date_to');

    // Apply filter based on type
    switch(type) {
        case 'all':
            // No additional filters needed
            break;
        case 'admin':
            params.set('role', 'admin');
            break;
        case 'user':
            params.set('role', 'user');
            break;
        case 'recent':
            // Filter users from last 30 days
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
            params.set('date_from', thirtyDaysAgo.toISOString().split('T')[0]);
            break;
        case 'customers':
            params.set('filter', 'has_orders');
            break;
    }

    // Reset to first page
    params.delete('page');

    // Navigate to filtered URL
    url.search = params.toString();
    window.location.href = url.toString();
}

function toggleAdmin(userId, makeAdmin) {
    const action = makeAdmin ? 'cấp quyền admin cho' : 'gỡ quyền admin của';
    const message = `Bạn có chắc muốn ${action} người dùng này?`;

    if (confirm(message)) {
        const form = document.getElementById('toggleAdminForm');
        form.action = `/admin/users/${userId}/toggle-admin`;
        form.submit();
    }
}

function deleteUser(userId) {
    if (confirm('Bạn có chắc muốn xóa người dùng này?\n\nLưu ý: Hành động này không thể hoàn tác!')) {
        const form = document.getElementById('deleteUserForm');
        form.action = `/admin/users/${userId}`;
        form.submit();
    }
}

function exportUsers() {
    // Get current filters
    const url = new URL(window.location);
    const params = new URLSearchParams(url.search);

    // Create export URL with current filters
    const exportUrl = new URL('/admin/users/export', window.location.origin);
    exportUrl.search = params.toString();

    // Download the file
    window.location.href = exportUrl.toString();
}

// Highlight active filter on page load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const role = urlParams.get('role');
    const filter = urlParams.get('filter');
    const dateFrom = urlParams.get('date_from');

    // Determine which card should be active
    let activeType = 'all';

    if (role === 'admin') {
        activeType = 'admin';
    } else if (role === 'user') {
        activeType = 'user';
    } else if (filter === 'has_orders') {
        activeType = 'customers';
    } else if (dateFrom) {
        // Check if it's recent filter (approximately 30 days)
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
        const filterDate = new Date(dateFrom);

        if (Math.abs(filterDate - thirtyDaysAgo) < 86400000) { // Within 1 day difference
            activeType = 'recent';
        }
    }

    // Add active class to the appropriate card
    const cards = document.querySelectorAll('.stat-card');
    cards.forEach((card, index) => {
        const types = ['all', 'admin', 'user', 'recent', 'customers'];
        if (types[index] === activeType) {
            card.classList.add('active');
        }
    });
});
</script>
@endpush
