@extends('admin.layouts.app')

@section('title', 'Quản lý Sản phẩm - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-box me-2"></i>Quản lý Sản phẩm
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm Sản phẩm
        </a>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" class="form-control" name="search"
                       value="{{ request('search') }}" placeholder="Tên sản phẩm, SKU...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Danh mục</label>
                <select class="form-select" name="category">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select class="form-select" name="status">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tạm dừng</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-body">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td><strong>{{ $product->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->primary_image_url)
                                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}"
                                             class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                        @if($product->sku)
                                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                                        @endif
                                        @if($product->featured)
                                            <span class="badge bg-warning text-dark ms-1">Nổi bật</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $product->category->name }}</span>
                            </td>
                            <td>
                                @if($product->sale_price < $product->price)
                                    <div>
                                        <span class="text-decoration-line-through text-muted small">{{ number_format($product->price) }}₫</span>
                                    </div>
                                    <strong class="text-success">{{ number_format($product->sale_price) }}₫</strong>
                                @else
                                    <strong>{{ number_format($product->price) }}₫</strong>
                                @endif
                            </td>
                            <td>
                                @if($product->stock <= 10)
                                    <span class="badge bg-danger">{{ $product->stock }}</span>
                                @elseif($product->stock <= 50)
                                    <span class="badge bg-warning">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->status)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Tạm dừng</span>
                                @endif
                            </td>
                            <td>{{ $product->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.products.show', $product) }}"
                                       class="btn btn-outline-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="btn btn-outline-warning" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form class="d-inline" action="{{ route('admin.products.destroy', $product) }}"
                                          method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-5x text-muted mb-3"></i>
                <h5>Chưa có sản phẩm nào</h5>
                <p class="text-muted">Hãy thêm sản phẩm đầu tiên cho cửa hàng của bạn</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Thêm Sản phẩm
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Product search script loaded');

    // Manual submit button
    $('.btn-outline-primary').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        console.log('Search form submitted', {
            search: $('input[name="search"]').val(),
            category: $('select[name="category"]').val(),
            status: $('select[name="status"]').val()
        });
        form.submit();
    });

    // Auto submit form on search input (with longer delay)
    $('input[name="search"]').on('keyup', function() {
        var searchForm = $(this).closest('form');
        var searchValue = $(this).val();

        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(function() {
            if (searchValue.length >= 2 || searchValue.length === 0) {
                console.log('Auto submitting search:', searchValue);
                searchForm.submit();
            }
        }, 1000);
    });

    // Submit form on select change
    $('select[name="category"], select[name="status"]').on('change', function() {
        console.log('Filter changed:', $(this).attr('name'), $(this).val());
        $(this).closest('form').submit();
    });

    // Clear button functionality
    $('.btn-outline-secondary').on('click', function(e) {
        e.preventDefault();
        console.log('Clear button clicked');
        $('input[name="search"]').val('');
        $('select[name="category"]').val('');
        $('select[name="status"]').val('');
        $(this).closest('form').submit();
    });
});
</script>
@endpush
