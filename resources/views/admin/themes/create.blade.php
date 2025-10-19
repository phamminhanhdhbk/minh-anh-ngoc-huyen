@extends('admin.layouts.app')

@section('title', 'Tạo Theme Mới')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tạo Theme Mới</h1>
        <a href="{{ route('admin.themes.index') }}" class="btn btn-secondary">
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
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin Theme</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.themes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="name">Tên Theme <span class="text-danger">*</span></label>
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
                            <label for="view_path">View Path <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('view_path') is-invalid @enderror"
                                   id="view_path"
                                   name="view_path"
                                   value="{{ old('view_path') }}"
                                   required
                                   placeholder="welcome">
                            <small class="form-text text-muted">Đường dẫn đến blade template (vd: welcome, themes.myTheme.home)</small>
                            @error('view_path')
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
                            <label for="author">Tác giả</label>
                            <input type="text"
                                   class="form-control @error('author') is-invalid @enderror"
                                   id="author"
                                   name="author"
                                   value="{{ old('author') }}"
                                   placeholder="VoShop Team">
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="version">Version</label>
                            <input type="text"
                                   class="form-control @error('version') is-invalid @enderror"
                                   id="version"
                                   name="version"
                                   value="{{ old('version', '1.0.0') }}"
                                   placeholder="1.0.0">
                            @error('version')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="thumbnail">Thumbnail</label>
                            <input type="file"
                                   class="form-control-file @error('thumbnail') is-invalid @enderror"
                                   id="thumbnail"
                                   name="thumbnail"
                                   accept="image/*">
                            <small class="form-text text-muted">Ảnh xem trước theme (JPEG, PNG, JPG, GIF - Max: 2MB)</small>
                            @error('thumbnail')
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
                                <input type="hidden" name="is_default" value="0">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="is_default"
                                       name="is_default"
                                       value="1"
                                       {{ old('is_default') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_default">
                                    Đặt làm theme mặc định
                                </label>
                                <small class="form-text text-muted">Theme mặc định sẽ được sử dụng khi không có theme nào được kích hoạt</small>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo Theme
                            </button>
                            <a href="{{ route('admin.themes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Guide -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i> Hướng dẫn
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold">View Path</h6>
                    <p class="small mb-3">
                        Đường dẫn đến file blade template. Ví dụ:
                        <ul class="small">
                            <li><code>welcome</code> → <code>resources/views/welcome.blade.php</code></li>
                            <li><code>themes.modern.home</code> → <code>resources/views/themes/modern/home.blade.php</code></li>
                        </ul>
                    </p>

                    <h6 class="font-weight-bold">Slug</h6>
                    <p class="small mb-3">
                        Định danh unique cho theme. Nên dùng chữ thường, không dấu, dùng dấu gạch ngang.
                        <br>Ví dụ: <code>gradient-modern</code>, <code>classic-theme</code>
                    </p>

                    <h6 class="font-weight-bold">Theme Structure</h6>
                    <p class="small mb-3">
                        Tạo folder mới trong <code>resources/views/themes/</code> cho mỗi theme:
                    </p>
                    <pre class="bg-light p-2 rounded small">themes/
├── gradient/
│   └── home.blade.php
├── modern/
│   └── home.blade.php
└── classic/
    └── home.blade.php</pre>

                    <h6 class="font-weight-bold">Thumbnail</h6>
                    <p class="small mb-0">
                        Upload ảnh xem trước theme để dễ nhận biết. Kích thước khuyến nghị: 800x600px
                    </p>
                </div>
            </div>

            <!-- Example -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-code"></i> Ví dụ Theme
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2"><strong>Tên Theme:</strong> Modern Blue</p>
                    <p class="small mb-2"><strong>Slug:</strong> modern-blue</p>
                    <p class="small mb-2"><strong>View Path:</strong> themes.modernblue.home</p>
                    <p class="small mb-2"><strong>Description:</strong> Theme hiện đại với màu xanh dương chủ đạo</p>
                    <p class="small mb-0"><strong>Author:</strong> Your Name</p>

                    <hr>

                    <p class="small text-muted mb-0">
                        <i class="fas fa-lightbulb text-warning"></i>
                        Sau khi tạo theme, bạn cần tạo file blade template tương ứng trong thư mục <code>resources/views/</code>
                    </p>
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

// Preview image before upload
document.getElementById('thumbnail').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            // You can add image preview here if needed
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush
@endsection
