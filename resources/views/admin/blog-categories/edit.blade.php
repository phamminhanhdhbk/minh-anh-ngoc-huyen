@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Danh mục Blog')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Chỉnh sửa Danh mục Blog</h2>
                <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.blog-categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug (URL)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                   id="slug" name="slug" value="{{ old('slug', $category->slug) }}"
                                   placeholder="Để trống để tự động tạo từ tên">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Slug sẽ được tự động tạo nếu để trống</small>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="image">Hình ảnh</label>
                            @if($category->image)
                                <div class="mb-2">
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="img-thumbnail" style="max-width: 100%; max-height: 200px;">
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                       id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                <label class="custom-file-label" for="image">Chọn hình ảnh mới...</label>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 100%; max-height: 200px;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="order">Thứ tự hiển thị</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror"
                                   id="order" name="order" value="{{ old('order', $category->order) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="status"
                                       name="status" value="1" {{ old('status', $category->status) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status">Hiển thị danh mục</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật danh mục
                    </button>
                    <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#preview').attr('src', e.target.result);
            $('#imagePreview').show();
        }
        reader.readAsDataURL(input.files[0]);

        // Update label
        var fileName = input.files[0].name;
        $(input).next('.custom-file-label').html(fileName);
    }
}

// Auto-generate slug from name
$('#name').on('input', function() {
    var slug = $(this).val()
        .toLowerCase()
        .replace(/á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a')
        .replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e')
        .replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i')
        .replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o')
        .replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u')
        .replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y')
        .replace(/đ/gi, 'd')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
    $('#slug').val(slug);
});
</script>
@endpush
@endsection
