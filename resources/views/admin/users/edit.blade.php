@extends('admin.layouts.app')

@section('title', 'Sửa Người dùng - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-edit me-2"></i>Sửa Người dùng: {{ $user->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info">
            <i class="fas fa-eye me-2"></i>Xem chi tiết
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $user->name) }}"
                               placeholder="Nhập họ và tên..." required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}"
                               placeholder="user@example.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Đổi mật khẩu</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Để trống nếu không muốn thay đổi mật khẩu
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password"
                                   placeholder="Nhập mật khẩu mới...">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tối thiểu 6 ký tự (để trống nếu không đổi)</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation"
                                   placeholder="Nhập lại mật khẩu mới...">
                            <div class="form-text">Nhập lại mật khẩu để xác nhận</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Phân quyền</h5>
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin"
                               value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                               {{ ($user->id === auth()->id()) ? 'disabled' : '' }}>
                        <label class="form-check-label" for="is_admin">
                            <strong>Cấp quyền Admin</strong>
                        </label>
                        @if($user->id === auth()->id())
                            <div class="form-text text-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Không thể thay đổi quyền của chính bạn
                            </div>
                        @else
                            <div class="form-text">
                                Admin có thể truy cập tất cả tính năng quản trị, bao gồm quản lý người dùng, sản phẩm và đơn hàng.
                            </div>
                        @endif
                    </div>

                    @if(!$user->is_admin || $user->id !== auth()->id())
                    <div class="alert alert-warning mt-3" id="adminWarning" style="{{ old('is_admin', $user->is_admin) ? '' : 'display: none;' }}">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Lưu ý:</strong> Quyền Admin cho phép truy cập toàn bộ hệ thống. Chỉ cấp cho người đáng tin cậy.
                    </div>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Cập nhật
                </button>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thông tin người dùng</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                </div>

                <p><strong>ID:</strong> {{ $user->id }}</p>
                <p><strong>Vai trò hiện tại:</strong>
                    @if($user->is_admin)
                        <span class="badge bg-danger">Admin</span>
                    @else
                        <span class="badge bg-secondary">Khách hàng</span>
                    @endif
                </p>
                <p><strong>Ngày đăng ký:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Cập nhật cuối:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                <p><strong>Xác thực email:</strong>
                    @if($user->email_verified_at)
                        <span class="badge bg-success">Đã xác thực</span>
                        <br><small class="text-muted">{{ $user->email_verified_at->format('d/m/Y H:i') }}</small>
                    @else
                        <span class="badge bg-warning">Chưa xác thực</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thống kê hoạt động</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Tổng đơn hàng:</span>
                    <strong>{{ $user->orders()->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Đơn hoàn thành:</span>
                    <strong>{{ $user->orders()->where('status', 'completed')->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tổng chi tiêu:</span>
                    <strong>{{ number_format($user->orders()->where('status', 'completed')->sum('total')) }}₫</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Giỏ hàng:</span>
                    <strong>{{ $user->carts()->count() }} sản phẩm</strong>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Thao tác nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($user->id !== auth()->id())
                        @if(!$user->is_admin)
                            <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-success w-100"
                                        onclick="return confirm('Cấp quyền admin cho người dùng này?')">
                                    <i class="fas fa-user-shield me-2"></i>Cấp quyền Admin
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-warning w-100"
                                        onclick="return confirm('Gỡ quyền admin của người dùng này?')">
                                    <i class="fas fa-user-minus me-2"></i>Gỡ quyền Admin
                                </button>
                            </form>
                        @endif

                        @if(!$user->orders()->exists())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Bạn có chắc muốn xóa người dùng này?\n\nHành động này không thể hoàn tác!')">
                                    <i class="fas fa-trash me-2"></i>Xóa người dùng
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info py-2 mb-0">
                                <small><i class="fas fa-info-circle me-1"></i> Không thể xóa người dùng có đơn hàng</small>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning py-2 mb-0">
                            <small><i class="fas fa-exclamation-triangle me-1"></i> Đây là tài khoản của bạn</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide admin warning (only if not current user)
    @if($user->id !== auth()->id())
    $('#is_admin').change(function() {
        if ($(this).is(':checked')) {
            $('#adminWarning').show();
        } else {
            $('#adminWarning').hide();
        }
    });
    @endif

    // Password confirmation validation
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmation = $(this).val();

        if (password && confirmation && password !== confirmation) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Mật khẩu xác nhận không khớp</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    // Password strength indicator
    $('#password').on('input', function() {
        const password = $(this).val();

        if (password.length === 0) {
            $('#strengthIndicator').remove();
            return;
        }

        let strength = 0;

        if (password.length >= 6) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        let strengthText = '';
        let strengthClass = '';

        switch (strength) {
            case 0:
            case 1:
                strengthText = 'Yếu';
                strengthClass = 'text-danger';
                break;
            case 2:
            case 3:
                strengthText = 'Trung bình';
                strengthClass = 'text-warning';
                break;
            case 4:
            case 5:
                strengthText = 'Mạnh';
                strengthClass = 'text-success';
                break;
        }

        $('#strengthIndicator').remove();
        $(this).after(`<div id="strengthIndicator" class="form-text ${strengthClass}">Độ mạnh: ${strengthText}</div>`);
    });
});
</script>
@endpush
