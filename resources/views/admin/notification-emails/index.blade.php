@extends('admin.layouts.app')

@section('title', 'Quản Lý Email Nhận Thông Báo - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-envelope me-2"></i>Quản Lý Email Nhận Thông Báo
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.notification-emails.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm Email
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Danh Sách Email ({{ $emails->total() }})</h5>
    </div>
    <div class="card-body p-0">
        @if($emails->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Tên</th>
                        <th>Loại</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emails as $email)
                    <tr>
                        <td>{{ $email->id }}</td>
                        <td>
                            <i class="fas fa-envelope me-1 text-primary"></i>
                            <strong>{{ $email->email }}</strong>
                        </td>
                        <td>{{ $email->name ?? '-' }}</td>
                        <td>
                            @if($email->type == 'order')
                                <span class="badge bg-success">Đơn hàng</span>
                            @elseif($email->type == 'contact')
                                <span class="badge bg-info">Liên hệ</span>
                            @else
                                <span class="badge bg-warning">Newsletter</span>
                            @endif
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="status-{{ $email->id }}"
                                       {{ $email->is_active ? 'checked' : '' }}
                                       onchange="toggleStatus({{ $email->id }})">
                                <label class="form-check-label" for="status-{{ $email->id }}">
                                    <span class="badge {{ $email->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $email->is_active ? 'Bật' : 'Tắt' }}
                                    </span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($email->note, 30) }}</small>
                        </td>
                        <td>
                            <small class="text-muted">{{ $email->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.notification-emails.edit', $email) }}" 
                                   class="btn btn-outline-info" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.notification-emails.destroy', $email) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa email này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $emails->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
            <p class="text-muted">Chưa có email nào được thêm.</p>
            <a href="{{ route('admin.notification-emails.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm Email Đầu Tiên
            </a>
        </div>
        @endif
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông Tin</h5>
    </div>
    <div class="card-body">
        <h6>Loại Email:</h6>
        <ul>
            <li><span class="badge bg-success">Đơn hàng</span> - Nhận thông báo khi có đơn hàng mới</li>
            <li><span class="badge bg-info">Liên hệ</span> - Nhận thông báo khi có liên hệ từ khách hàng</li>
            <li><span class="badge bg-warning">Newsletter</span> - Nhận bản tin định kỳ</li>
        </ul>
        <hr>
        <p class="mb-0">
            <i class="fas fa-lightbulb text-warning me-2"></i>
            <strong>Lưu ý:</strong> Chỉ những email có trạng thái "Bật" mới nhận được thông báo.
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(emailId) {
    fetch(`/admin/notification-emails/${emailId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const badge = document.querySelector(`#status-${emailId}`).nextElementSibling.querySelector('.badge');
            if(data.is_active) {
                badge.classList.remove('bg-secondary');
                badge.classList.add('bg-success');
                badge.textContent = 'Bật';
            } else {
                badge.classList.remove('bg-success');
                badge.classList.add('bg-secondary');
                badge.textContent = 'Tắt';
            }
            
            showToast('success', data.message, 'Thành công');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('danger', 'Có lỗi xảy ra!', 'Lỗi');
    });
}
</script>
@endpush
