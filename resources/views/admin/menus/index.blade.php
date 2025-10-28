@extends('admin.layouts.app')

@section('title', 'Quản lý Menu')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Menu</h1>
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo Menu Mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách Menu</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Tên Menu</th>
                            <th width="15%">Slug</th>
                            <th width="15%">Vị trí</th>
                            <th width="10%">Số mục</th>
                            <th width="10%">Thứ tự</th>
                            <th width="10%">Trạng thái</th>
                            <th width="15%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                            <tr>
                                <td>{{ $menu->id }}</td>
                                <td>
                                    <strong>{{ $menu->name }}</strong>
                                    @if($menu->description)
                                        <br><small class="text-muted">{{ Str::limit($menu->description, 50) }}</small>
                                    @endif
                                </td>
                                <td><code>{{ $menu->slug }}</code></td>
                                <td>
                                    @if($menu->location)
                                        <span class="badge badge-primary">{{ $menu->location }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $menu->all_items_count }} mục</span>
                                </td>
                                <td>{{ $menu->order }}</td>
                                <td>
                                    @if($menu->is_active)
                                        <span class="badge badge-success">Hoạt động</span>
                                    @else
                                        <span class="badge badge-danger">Ẩn</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.menus.edit', $menu->id) }}"
                                           class="btn btn-info"
                                           title="Chỉnh sửa & Quản lý mục">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.menus.destroy', $menu->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa menu này? Tất cả mục menu con cũng sẽ bị xóa.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Chưa có menu nào. <a href="{{ route('admin.menus.create') }}">Tạo menu đầu tiên</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($menus->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $menus->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
/* Better badge visibility */
.badge-success {
    background-color: #1cc88a !important;
    color: white !important;
}
.badge-danger {
    background-color: #e74a3b !important;
    color: white !important;
}
.badge-info {
    background-color: #36b9cc !important;
    color: white !important;
}
.badge-secondary {
    background-color: #858796 !important;
    color: white !important;
}

/* Table styling */
.table-hover tbody tr:hover {
    background-color: #f8f9fc;
}
</style>
@endpush
@endsection
