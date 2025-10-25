@extends('admin.layouts.app')

@section('title', 'Sửa Sản phẩm - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>Sửa Sản phẩm: {{ $product->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info">
            <i class="fas fa-eye me-2"></i>Xem chi tiết
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $product->name) }}"
                               placeholder="Nhập tên sản phẩm..." required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="5"
                                  placeholder="Nhập mô tả chi tiết sản phẩm...">{{ old('description', $product->description) }}</textarea>
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
                                <option value="{{ $category->id }}"
                                    {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
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
                                <input type="text" class="form-control @error('price') is-invalid @enderror price-input"
                                       id="price" name="price_display" value="{{ old('price', $product->price) }}"
                                       placeholder="0" required>
                                <span class="input-group-text">₫</span>
                            </div>
                            <input type="hidden" id="price_hidden" name="price" value="{{ old('price', $product->price) }}">
                            <div class="price-text mt-2" style="font-size: 0.9rem; color: #666;"></div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('sale_price') is-invalid @enderror price-input"
                                       id="sale_price" name="sale_price_display" value="{{ old('sale_price', $product->sale_price) }}"
                                       placeholder="0">
                                <span class="input-group-text">₫</span>
                            </div>
                            <input type="hidden" id="sale_price_hidden" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                            <div class="price-text mt-2" style="font-size: 0.9rem; color: #666;"></div>
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
                                   id="stock" name="stock" value="{{ old('stock', $product->stock) }}"
                                   min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sku" class="form-label">Mã SKU</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   id="sku" name="sku" value="{{ old('sku', $product->sku) }}"
                                   placeholder="VD: SP001, PHONE001...">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                               id="image" name="image" value="{{ old('image', $product->image) }}"
                               placeholder="https://example.com/image.jpg">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nhập URL hình ảnh từ internet (tuỳ chọn - để tương thích ngược)</div>
                    </div>

                    @if($product->images->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh hiện tại</label>
                        <div class="row" id="currentImages">
                            @foreach($product->images as $index => $productImage)
                            <div class="col-md-3 mb-3" data-image-id="{{ $productImage->id }}">
                                <div class="card">
                                    <img src="{{ $productImage->image_url }}" alt="{{ $productImage->alt_text }}"
                                         class="card-img-top" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">{{ $productImage->alt_text }}</small>
                                            @if($productImage->is_primary)
                                                <span class="badge bg-primary">Ảnh chính</span>
                                            @endif
                                        </div>
                                        <div class="btn-group w-100 mt-2" role="group">
                                            @if(!$productImage->is_primary)
                                            <button type="button" class="btn btn-sm btn-outline-primary set-primary" data-id="{{ $productImage->id }}">
                                                Đặt chính
                                            </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-image" data-id="{{ $productImage->id }}">
                                                Xóa
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @elseif($product->image)
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh hiện tại (cũ)</label>
                        <div>
                            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                 class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="images" class="form-label">Thêm Nhiều Hình Ảnh Mới</label>
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

                        <!-- New Image Preview -->
                        <div id="newImagePreview" class="mt-3 row"></div>
                    </div>

                    <div class="mb-3">
                        <label for="image_urls" class="form-label">Thêm URLs Hình Ảnh Mới</label>
                        <textarea class="form-control @error('image_urls') is-invalid @enderror"
                                  id="image_urls" name="image_urls" rows="4"
                                  placeholder="Nhập mỗi URL trên một dòng:&#10;https://example.com/image1.jpg&#10;https://example.com/image2.jpg">{{ old('image_urls') }}</textarea>
                        @error('image_urls')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nhập mỗi URL ảnh mới trên một dòng (tuỳ chọn)</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="status" name="status"
                                       value="1" {{ old('status', $product->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Kích hoạt sản phẩm
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="featured" name="featured"
                                       value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}>
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
                    <i class="fas fa-save me-2"></i>Cập nhật sản phẩm
                </button>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thông tin sản phẩm</h6>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $product->id }}</p>
                <p><strong>Slug:</strong> {{ $product->slug }}</p>
                <p><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Cập nhật:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>
                @if($product->sale_price)
                <p><strong>Giảm giá:</strong>
                    <span class="badge bg-success">
                        {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                    </span>
                </p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Hướng dẫn</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Tên sản phẩm nên rõ ràng, dễ hiểu</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Mô tả chi tiết giúp khách hàng hiểu sản phẩm</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Giá khuyến mãi phải nhỏ hơn giá gốc</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Slug sẽ tự động cập nhật theo tên</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Sản phẩm nổi bật sẽ hiển thị trên trang chủ</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@if($product->orderItems()->exists())
<div class="alert alert-warning mt-4">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Chú ý:</strong> Sản phẩm này đã có đơn hàng. Thay đổi giá có thể ảnh hưởng đến báo cáo doanh thu.
</div>
@endif
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto calculate discount
    $('#price, #sale_price').on('input', function() {
        const price = parseInt($('#price_hidden').val()) || 0;
        const salePrice = parseInt($('#sale_price_hidden').val()) || 0;

        if (salePrice > 0 && salePrice >= price) {
            alert('Giá khuyến mãi phải nhỏ hơn giá gốc!');
            $('#sale_price').val('');
            $('#sale_price_hidden').val('');
        }
    });

    // Confirm if changing active status
    $('#status').change(function() {
        if (!$(this).is(':checked')) {
            if (!confirm('Bạn có chắc muốn tạm ngưng sản phẩm này?')) {
                $(this).prop('checked', true);
            }
        }
    });

    // Preview new images
    $('#images').on('change', function() {
        const files = this.files;
        const preview = $('#newImagePreview');
        preview.empty();

        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imageHtml = `
                            <div class="col-md-3 mb-3">
                                <div class="card border-success">
                                    <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-success">Mới: ${file.name}</small>
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

    // Delete image
    $('.delete-image').on('click', function() {
        const imageId = $(this).data('id');
        const imageCard = $(this).closest('[data-image-id]');

        if (confirm('Bạn có chắc muốn xóa ảnh này?')) {
            $.ajax({
                url: `/admin/product-images/${imageId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        imageCard.fadeOut(300, function() {
                            $(this).remove();
                        });

                        // Show message
                        const alertHtml = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Đã xóa ảnh thành công!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        $('#currentImages').before(alertHtml);
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra khi xóa ảnh!');
                }
            });
        }
    });

    // Set primary image
    $('.set-primary').on('click', function() {
        const imageId = $(this).data('id');
        const button = $(this);

        $.ajax({
            url: `/admin/product-images/${imageId}/primary`,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Remove all primary badges
                    $('.badge.bg-primary').remove();
                    $('.set-primary').removeClass('d-none').show();

                    // Add primary badge to this image
                    button.closest('.card-body').find('.d-flex').append('<span class="badge bg-primary">Ảnh chính</span>');
                    button.hide();

                    // Show message
                    const alertHtml = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Đã đặt ảnh chính thành công!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    $('#currentImages').before(alertHtml);
                }
            },
            error: function() {
                alert('Có lỗi xảy ra khi đặt ảnh chính!');
            }
        });
    });
    // Format price input and display in words
    function formatPriceInput(selector, hiddenSelector) {
        const input = $(selector);
        const hiddenInput = $(hiddenSelector);
        const display = input.closest('.mb-3').find('.price-text');

        console.log('formatPriceInput called for', selector);
        console.log('Input element:', input);
        console.log('Hidden input element:', hiddenInput);
        console.log('Display element:', display);

        function updateDisplay() {
            let value = input.val();
            console.log('Current value:', value);
            
            if (!value || value === '') {
                display.html('');
                input.val('');
                hiddenInput.val('');
                return;
            }

            // Remove all non-digit characters to get clean number
            const cleanString = value.replace(/\D/g, '');
            const cleanValue = parseInt(cleanString) || 0;
            console.log('Clean value:', cleanValue);
            
            if (cleanValue === 0) {
                display.html('');
                input.val('');
                hiddenInput.val('');
                return;
            }

            // Format with thousands separator (e.g., 4000000 -> 4.000.000)
            const formatted = new Intl.NumberFormat('vi-VN').format(cleanValue);
            console.log('Formatted:', formatted);
            
            // Update input to show formatted value
            input.val(formatted);
            
            // Update hidden input with clean number value for backend
            hiddenInput.val(cleanValue);
            console.log('Hidden input value:', cleanValue);
            
            // Convert to words in Vietnamese
            const priceInWords = numberToVietnameseCurrency(cleanValue);
            console.log('Price in words:', priceInWords);
            
            const displayText = `<strong>${formatted}₫</strong> (${priceInWords})`;
            console.log('Display text:', displayText);
            
            display.html(displayText);
        }

        // Format when losing focus
        input.on('blur', updateDisplay);
        
        // Allow typing only numbers and format on input
        input.on('input', function() {
            let value = $(this).val();
            // Remove all non-digit characters
            let cleanValue = value.replace(/\D/g, '');
            $(this).val(cleanValue);
        });
        
        // Format when pressing enter
        input.on('keypress', function(e) {
            if (e.which === 13) {
                updateDisplay();
            }
        });
        
        // Initialize on page load
        updateDisplay();
    }

    // Convert number to Vietnamese currency words
    function numberToVietnameseCurrency(num) {
        if (num === 0 || !num) return '';

        // Simple version - just show the number formatted
        const units = ['', 'nghìn', 'triệu', 'tỷ'];
        let parts = [];
        let unitIndex = 0;

        while (num > 0 && unitIndex < units.length) {
            const remainder = num % 1000;
            if (remainder > 0) {
                parts.unshift(remainder + (units[unitIndex] ? ' ' + units[unitIndex] : ''));
            }
            num = Math.floor(num / 1000);
            unitIndex++;
        }

        return parts.join(' ');
    }

    // Initialize price formatting
    console.log('Initializing price formatting...');
    
    // Test if jQuery and selectors work
    console.log('Price input:', $('#price'));
    console.log('Sale price input:', $('#sale_price'));
    
    formatPriceInput('#price', '#price_hidden');
    formatPriceInput('#sale_price', '#sale_price_hidden');
    
    console.log('Price formatting initialized');
});
</script>
@endpush
