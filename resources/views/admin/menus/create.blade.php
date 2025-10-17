@extends('admin.layouts.app')

@section('title', 'Tạo Menu Mới')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tạo Menu Mới</h1>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Có lỗi xảy ra:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin Menu</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">Tên Menu <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug') }}"
                                   required>
                            <small class="form-text text-muted">Slug sẽ được tạo tự động nếu để trống</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="location">Vị trí</label>
                            <select class="form-control @error('location') is-invalid @enderror"
                                    id="location"
                                    name="location">
                                <option value="">-- Chọn vị trí --</option>
                                <option value="header" {{ old('location') == 'header' ? 'selected' : '' }}>Header (Đầu trang)</option>
                                <option value="footer" {{ old('location') == 'footer' ? 'selected' : '' }}>Footer (Chân trang)</option>
                                <option value="sidebar" {{ old('location') == 'sidebar' ? 'selected' : '' }}>Sidebar (Thanh bên)</option>
                                <option value="mobile" {{ old('location') == 'mobile' ? 'selected' : '' }}>Mobile Menu</option>
                            </select>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="order">Thứ tự</label>
                            <input type="number"
                                   class="form-control @error('order') is-invalid @enderror"
                                   id="order"
                                   name="order"
                                   value="{{ old('order', 0) }}"
                                   min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    Hiển thị menu
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo Menu
                            </button>
                            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i> Hướng dẫn
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold">Tạo Menu</h6>
                    <p class="small mb-3">Sau khi tạo menu, bạn có thể thêm các mục menu (menu items) vào menu này.</p>

                    <h6 class="font-weight-bold">Vị trí Menu</h6>
                    <p class="small mb-3">Chọn vị trí hiển thị menu trên website (header, footer, sidebar, mobile).</p>

                    <h6 class="font-weight-bold">Slug</h6>
                    <p class="small mb-0">Slug được dùng để gọi menu trong code. Nên sử dụng ký tự không dấu, không khoảng trắng.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto generate slug from name
document.getElementById('name').addEventListener('input', function() {
    let name = this.value;
    let slug = name.toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    document.getElementById('slug').value = slug;
});
</script>
@endpush
@endsection
