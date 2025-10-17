@extends('admin.layouts.app')

@section('title', 'Chi tiết danh mục - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-list me-2"></i>Chi tiết danh mục: {{ $category->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary me-2">
            <i class="fas fa-edit me-2"></i>Chỉnh sửa
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Category Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Thông tin danh mục
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên danh mục:</label>
                            <p class="mb-0">{{ $category->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Slug:</label>
                            <p class="mb-0">
                                <code>{{ $category->slug }}</code>
                            </p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả:</label>
                            <p class="mb-0">{{ $category->description ?: 'Không có mô tả' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái:</label>
                            <p class="mb-0">
                                @if($category->status)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-danger">Tạm dừng</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ngày tạo:</label>
                            <p class="mb-0">{{ $category->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products in Category -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>Sản phẩm trong danh mục ({{ $products->total() }})
                </h5>
                <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Thêm sản phẩm
                </a>
            </div>
            <div class="card-body">
                @if($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Tồn kho</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th width="120">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td><strong>{{ $product->id }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                                     class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $product->name }}</h6>
                                                @if($product->sku)
                                                    <small class="text-muted">SKU: {{ $product->sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->sale_price < $product->price)
                                            <div>
                                                <span class="text-decoration-line-through text-muted small">{{ number_format($product->price) }}₫</span>
                                                <br><strong class="text-danger">{{ number_format($product->sale_price) }}₫</strong>
                                            </div>
                                        @else
                                            <strong>{{ number_format($product->price) }}₫</strong>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->status)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-danger">Tạm dừng</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.products.show', $product) }}"
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h5>Chưa có sản phẩm nào</h5>
                        <p class="text-muted">Danh mục này chưa có sản phẩm nào.</p>
                        <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Thống kê
                </h6>
            </div>
            <div class="card-body">
                @php
                $totalProducts = $category->products()->count();
                $activeProducts = $category->products()->where('status', 1)->count();

                // Calculate total sold using join
                $totalSold = \DB::table('order_items')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->where('products.category_id', $category->id)
                    ->sum('order_items.quantity');

                // Calculate total revenue
                $totalRevenue = \DB::table('order_items')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->where('products.category_id', $category->id)
                    ->sum(\DB::raw('order_items.quantity * order_items.product_price'));
                @endphp

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Tổng sản phẩm:</span>
                    <span class="fw-bold">{{ $totalProducts }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Sản phẩm hoạt động:</span>
                    <span class="fw-bold text-success">{{ $activeProducts }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Đã bán:</span>
                    <span class="fw-bold text-primary">{{ number_format($totalSold) }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span>Doanh thu:</span>
                    <span class="fw-bold text-warning">{{ number_format($totalRevenue) }}₫</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                    </a>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa danh mục
                    </a>
                    @if($category->products()->count() == 0)
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                          onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Xóa danh mục
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
