@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Theme: ' . $theme->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa Theme: {{ $theme->name }}</h1>
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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
                    <form action="{{ route('admin.themes.update', $theme->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Tên Theme <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $theme->name) }}"
                                   required>
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
                                   value="{{ old('slug', $theme->slug) }}"
                                   required>
                            <small class="form-text text-muted">Định danh unique cho theme</small>
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
                                   value="{{ old('view_path', $theme->view_path) }}"
                                   required>
                            <small class="form-text text-muted">Đường dẫn đến blade template (vd: welcome, themes.gradient.home)</small>
                            @error('view_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3">{{ old('description', $theme->description) }}</textarea>
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
                                   value="{{ old('author', $theme->author) }}">
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
                                   value="{{ old('version', $theme->version) }}"
                                   placeholder="1.0.0">
                            @error('version')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="thumbnail">Thumbnail</label>
                            @if($theme->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset($theme->thumbnail) }}"
                                         alt="{{ $theme->name }}"
                                         class="img-thumbnail"
                                         style="max-width: 300px;">
                                </div>
                            @endif
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
                                   value="{{ old('order', $theme->order) }}"
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
                                       {{ old('is_default', $theme->is_default) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_default">
                                    Đặt làm theme mặc định
                                </label>
                                <small class="form-text text-muted">Theme mặc định sẽ được sử dụng khi không có theme nào được kích hoạt</small>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật Theme
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
            <!-- Theme Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-cog"></i> Hành động
                    </h6>
                </div>
                <div class="card-body">
                    @if(!$theme->is_active)
                        <form action="{{ route('admin.themes.activate', $theme->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit"
                                    class="btn btn-success btn-block"
                                    onclick="return confirm('Bạn có chắc muốn kích hoạt theme này?')">
                                <i class="fas fa-check"></i> Kích hoạt Theme
                            </button>
                        </form>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Theme đang hoạt động
                        </div>
                    @endif

                    <a href="{{ route('admin.themes.preview', $theme->id) }}"
                       class="btn btn-info btn-block mb-3"
                       target="_blank">
                        <i class="fas fa-eye"></i> Xem trước Theme
                    </a>

                    @if(!$theme->is_active && !$theme->is_default)
                        <form action="{{ route('admin.themes.destroy', $theme->id) }}"
                              method="POST"
                              onsubmit="return confirm('Bạn có chắc muốn xóa theme này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> Xóa Theme
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Theme Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i> Thông tin Theme
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th width="40%">Slug:</th>
                            <td><code>{{ $theme->slug }}</code></td>
                        </tr>
                        <tr>
                            <th>View Path:</th>
                            <td><code>{{ $theme->view_path }}</code></td>
                        </tr>
                        <tr>
                            <th>Version:</th>
                            <td>{{ $theme->version }}</td>
                        </tr>
                        <tr>
                            <th>Author:</th>
                            <td>{{ $theme->author ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($theme->is_active)
                                    <span class="badge badge-success">Đang dùng</span>
                                @elseif($theme->is_default)
                                    <span class="badge badge-info">Mặc định</span>
                                @else
                                    <span class="badge badge-secondary">Không dùng</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tạo lúc:</th>
                            <td>{{ $theme->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Cập nhật:</th>
                            <td>{{ $theme->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Theme Settings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-sliders-h"></i> Cài đặt Theme
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.themes.updateSettings', $theme->id) }}" method="POST">
                        @csrf

                        @php
                            $settings = $theme->settings ?? [];
                        @endphp

                        @if($theme->slug === 'gradient-modern')
                            <div class="form-group">
                                <label for="primary_color">Primary Color</label>
                                <input type="color"
                                       class="form-control"
                                       id="primary_color"
                                       name="settings[primary_color]"
                                       value="{{ $settings['primary_color'] ?? '#667eea' }}">
                            </div>

                            <div class="form-group">
                                <label for="secondary_color">Secondary Color</label>
                                <input type="color"
                                       class="form-control"
                                       id="secondary_color"
                                       name="settings[secondary_color]"
                                       value="{{ $settings['secondary_color'] ?? '#764ba2' }}">
                            </div>

                            <div class="form-group">
                                <label for="accent_color">Accent Color</label>
                                <input type="color"
                                       class="form-control"
                                       id="accent_color"
                                       name="settings[accent_color]"
                                       value="{{ $settings['accent_color'] ?? '#f093fb' }}">
                            </div>
                        @else
                            <p class="text-muted">Theme này chưa có cài đặt tùy chỉnh.</p>
                        @endif

                        @if($theme->slug === 'gradient-modern')
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Lưu cài đặt
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Guide -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-lightbulb"></i> Hướng dẫn
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0 pl-3">
                        <li>View Path phải trỏ đến file blade template hợp lệ</li>
                        <li>Slug phải là duy nhất (unique)</li>
                        <li>Theme mặc định sẽ được dùng khi không có theme nào active</li>
                        <li>Không thể xóa theme đang hoạt động hoặc theme mặc định</li>
                        <li>Dùng "Xem trước" để kiểm tra theme trước khi kích hoạt</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto generate slug from name
    let userEditedSlug = false;

    $('#name').on('input', function() {
        if (!userEditedSlug) {
            let name = $(this).val();
            let slug = name.toLowerCase()
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
        }
    });

    // Track if user manually edits slug
    $('#slug').on('input', function() {
        userEditedSlug = true;
    });
});
</script>
@endpush
@endsection
