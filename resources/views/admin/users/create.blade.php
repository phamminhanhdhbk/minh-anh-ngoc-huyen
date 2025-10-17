@extends('admin.layouts.app')

@section('title', 'Thêm Người dùng - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus me-2"></i>Thêm Người dùng mới
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}"
                               placeholder="Nhập họ và tên..." required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="user@example.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Mật khẩu</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password"
                                   placeholder="Nhập mật khẩu..." required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tối thiểu 6 ký tự</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation"
                                   placeholder="Nhập lại mật khẩu..." required>
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
                               value="1" {{ old('is_admin') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_admin">
                            <strong>Cấp quyền Admin</strong>
                        </label>
                        <div class="form-text">
                            Admin có thể truy cập tất cả tính năng quản trị, bao gồm quản lý người dùng, sản phẩm và đơn hàng.
                        </div>
                    </div>

                    <div class="alert alert-warning mt-3" id="adminWarning" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Lưu ý:</strong> Quyền Admin cho phép truy cập toàn bộ hệ thống. Chỉ cấp cho người đáng tin cậy.
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Tạo người dùng
                </button>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Hướng dẫn</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Email phải là duy nhất trong hệ thống
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Mật khẩu tối thiểu 6 ký tự
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Admin có thể quản lý toàn bộ hệ thống
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Người dùng thường chỉ có thể mua hàng
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Có thể thay đổi quyền sau khi tạo
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Thống kê hệ thống</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Tổng người dùng:</span>
                    <strong>{{ \App\User::count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Admin:</span>
                    <strong>{{ \App\User::where('is_admin', true)->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Khách hàng:</span>
                    <strong>{{ \App\User::where('is_admin', false)->count() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide admin warning
    $('#is_admin').change(function() {
        if ($(this).is(':checked')) {
            $('#adminWarning').show();
        } else {
            $('#adminWarning').hide();
        }
    });

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
        if (password.length > 0) {
            $(this).after(`<div id="strengthIndicator" class="form-text ${strengthClass}">Độ mạnh: ${strengthText}</div>`);
        }
    });
});
</script>
@endpush
