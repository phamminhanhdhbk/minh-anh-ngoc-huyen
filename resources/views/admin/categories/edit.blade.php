@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa danh mục - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>Chỉnh sửa danh mục
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-info me-2">
            <i class="fas fa-eye me-2"></i>Xem chi tiết
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Thông tin danh mục
                </h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tên danh mục *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                   value="{{ $category->slug }}" readonly>
                            <div class="form-text">Slug sẽ được tự động tạo từ tên danh mục</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4"
                                  placeholder="Nhập mô tả cho danh mục...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">URL hình ảnh</label>
                        <input type="url" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" value="{{ old('image', $category->image) }}"
                               placeholder="https://example.com/image.jpg">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nhập URL hình ảnh đại diện cho danh mục</div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" name="status"
                                   value="1" {{ old('status', $category->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">
                                Kích hoạt danh mục
                            </label>
                        </div>
                        <div class="form-text">Chỉ danh mục được kích hoạt mới hiển thị trên website</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật danh mục
                        </button>

                        <div>
                            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-secondary me-2">
                                Hủy
                            </a>
                            @if($category->products()->count() == 0)
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-2"></i>Xóa danh mục
                            </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Preview -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Xem trước
                </h6>
            </div>
            <div class="card-body">
                <div class="category-preview">
                    <div class="mb-3" id="preview-image-container" style="{{ $category->image ? '' : 'display: none;' }}">
                        <img id="preview-image" src="{{ $category->image }}" alt="Preview"
                             class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                    </div>
                    <h6 id="preview-name">{{ $category->name }}</h6>
                    <p class="text-muted" id="preview-description">
                        {{ $category->description ?: 'Không có mô tả' }}
                    </p>
                    <div>
                        <span class="badge" id="preview-status">
                            {{ $category->status ? 'Hoạt động' : 'Tạm dừng' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Thống kê
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Tổng sản phẩm:</span>
                    <span class="fw-bold">{{ $category->products()->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Sản phẩm hoạt động:</span>
                    <span class="fw-bold text-success">{{ $category->products()->where('status', 1)->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Ngày tạo:</span>
                    <span class="fw-bold">{{ $category->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@if($category->products()->count() == 0)
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa danh mục <strong>{{ $category->name }}</strong>?</p>
                <p class="text-muted">Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Xóa danh mục
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto generate slug from name
    $('#name').on('input', function() {
        let name = $(this).val();
        let slug = name.toLowerCase()
            .replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, 'a')
            .replace(/[èéẹẻẽêềếệểễ]/g, 'e')
            .replace(/[ìíịỉĩ]/g, 'i')
            .replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, 'o')
            .replace(/[ùúụủũưừứựửữ]/g, 'u')
            .replace(/[ỳýỵỷỹ]/g, 'y')
            .replace(/đ/g, 'd')
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');

        $('#slug').val(slug);
        $('#preview-name').text(name || 'Tên danh mục');
    });

    // Update description preview
    $('#description').on('input', function() {
        let description = $(this).val();
        $('#preview-description').text(description || 'Không có mô tả');
    });

    // Update image preview
    $('#image').on('input', function() {
        let imageUrl = $(this).val();
        if (imageUrl) {
            $('#preview-image').attr('src', imageUrl);
            $('#preview-image-container').show();
        } else {
            $('#preview-image-container').hide();
        }
    });

    // Update status preview
    $('#status').on('change', function() {
        let isActive = $(this).is(':checked');
        let badge = $('#preview-status');

        if (isActive) {
            badge.removeClass('bg-danger').addClass('bg-success').text('Hoạt động');
        } else {
            badge.removeClass('bg-success').addClass('bg-danger').text('Tạm dừng');
        }
    });

    // Initialize status badge
    let isActive = $('#status').is(':checked');
    let badge = $('#preview-status');
    if (isActive) {
        badge.addClass('bg-success');
    } else {
        badge.addClass('bg-danger');
    }
});
</script>
@endpush
