@extends('admin.layouts.app')

@section('title', 'Thêm Sản phẩm - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>Thêm Sản phẩm mới
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}"
                               placeholder="Nhập tên sản phẩm..." required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="5"
                                  placeholder="Nhập mô tả chi tiết sản phẩm...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id" required>
                            <option value="">Chọn danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Giá và Tồn kho</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Giá gốc <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price') }}"
                                       min="0" step="1000" required>
                                <span class="input-group-text">₫</span>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('sale_price') is-invalid @enderror"
                                       id="sale_price" name="sale_price" value="{{ old('sale_price') }}"
                                       min="0" step="1000">
                                <span class="input-group-text">₫</span>
                            </div>
                            @error('sale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Để trống nếu không có khuyến mãi</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                   id="stock" name="stock" value="{{ old('stock', 0) }}"
                                   min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sku" class="form-label">Mã SKU</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   id="sku" name="sku" value="{{ old('sku') }}"
                                   placeholder="VD: SP001, PHONE001...">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Để trống để tự động tạo</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Hình ảnh và Cài đặt</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="image" class="form-label">URL Hình ảnh</label>
                        <input type="url" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" value="{{ old('image') }}"
                               placeholder="https://example.com/image.jpg">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nhập URL hình ảnh từ internet (tuỳ chọn - để tương thích ngược)</div>
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label">Upload Nhiều Hình Ảnh</label>
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                               id="images" name="images[]" multiple accept="image/*">
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle text-info"></i>
                            Hỗ trợ: JPEG, PNG, JPG, GIF, WebP. <strong>Tối đa 20MB/ảnh.</strong>
                            Nếu upload nhiều ảnh lớn, hãy đợi thanh progress hoàn tất.
                        </div>

                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-3 row"></div>
                    </div>

                    <div class="mb-3">
                        <label for="image_urls" class="form-label">URLs Nhiều Hình Ảnh</label>
                        <textarea class="form-control @error('image_urls') is-invalid @enderror"
                                  id="image_urls" name="image_urls" rows="4"
                                  placeholder="Nhập mỗi URL trên một dòng:&#10;https://example.com/image1.jpg&#10;https://example.com/image2.jpg">{{ old('image_urls') }}</textarea>
                        @error('image_urls')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nhập mỗi URL ảnh trên một dòng (tuỳ chọn)</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="status" name="status"
                                       value="1" {{ old('status', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Kích hoạt sản phẩm
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="featured" name="featured"
                                       value="1" {{ old('featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured">
                                    Sản phẩm nổi bật
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Lưu sản phẩm
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
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Tên sản phẩm nên rõ ràng, dễ hiểu</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Mô tả chi tiết giúp khách hàng hiểu sản phẩm</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Giá khuyến mãi phải nhỏ hơn giá gốc</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>SKU giúp quản lý sản phẩm dễ dàng</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Sản phẩm nổi bật sẽ hiển thị trên trang chủ</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto calculate discount
    $('#price, #sale_price').on('input', function() {
        const price = parseFloat($('#price').val()) || 0;
        const salePrice = parseFloat($('#sale_price').val()) || 0;

        if (salePrice > 0 && salePrice >= price) {
            alert('Giá khuyến mãi phải nhỏ hơn giá gốc!');
            $('#sale_price').val('');
        }
    });

    // Preview multiple images
    $('#images').on('change', function() {
        const files = this.files;
        const preview = $('#imagePreview');
        preview.empty();

        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imageHtml = `
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">${file.name}</small>
                                        ${i === 0 ? '<span class="badge bg-primary">Ảnh chính</span>' : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                        preview.append(imageHtml);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    });

    // Preview single image URL
    $('#image').on('input', function() {
        const url = $(this).val();
        // You can add single image preview functionality here
    });
});
</script>
@endpush
