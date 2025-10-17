@extends('admin.layouts.app')

@section('title', 'Chi tiết Sản phẩm - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye me-2"></i>Chi tiết Sản phẩm
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>Sửa
            </a>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                <i class="fas fa-trash me-2"></i>Xóa
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $product->name }}</h5>
                <div>
                    @if($product->status)
                        <span class="badge bg-success">Đang bán</span>
                    @else
                        <span class="badge bg-secondary">Tạm ngưng</span>
                    @endif
                    @if($product->featured)
                        <span class="badge bg-primary">Nổi bật</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if($product->images->count() > 0)
                            <!-- Main Image -->
                            @php
                                $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                            @endphp
                            <div class="mb-3">
                                <img id="mainImage" src="{{ $primaryImage->image_url }}" alt="{{ $primaryImage->alt_text }}"
                                     class="img-fluid rounded" style="max-height: 400px; width: 100%; object-fit: cover;">
                            </div>

                            <!-- Thumbnail Gallery -->
                            @if($product->images->count() > 1)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($product->images as $image)
                                <img src="{{ $image->image_url }}" alt="{{ $image->alt_text }}"
                                     class="img-thumbnail cursor-pointer thumbnail-image {{ $image->is_primary ? 'active-thumb' : '' }}"
                                     style="width: 80px; height: 80px; object-fit: cover;"
                                     onclick="changeMainImage('{{ $image->image_url }}', '{{ $image->alt_text }}', this)">
                                @endforeach
                            </div>
                            @endif
                        @elseif($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                 class="img-fluid rounded mb-3" style="max-height: 400px; width: 100%; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3"
                                 style="height: 300px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6>Thông tin cơ bản</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>{{ $product->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>SKU:</strong></td>
                                <td>{{ $product->sku ?: 'Chưa có' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Slug:</strong></td>
                                <td><code>{{ $product->slug }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Danh mục:</strong></td>
                                <td>
                                    <a href="{{ route('admin.categories.show', $product->category) }}" class="text-decoration-none">
                                        {{ $product->category->name }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Ngày tạo:</strong></td>
                                <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Cập nhật:</strong></td>
                                <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($product->description)
                <div class="mt-4">
                    <h6>Mô tả sản phẩm</h6>
                    <div class="border-start border-primary border-3 ps-3">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order History -->
        @if($product->orderItems()->exists())
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Lịch sử đơn hàng</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Đơn hàng</th>
                                <th>Khách hàng</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Tổng</th>
                                <th>Ngày</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->orderItems()->with('order.user')->latest()->take(10)->get() as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $item->order) }}" class="text-decoration-none">
                                        #{{ $item->order->id }}
                                    </a>
                                </td>
                                <td>
                                    {{ $item->order->user ? $item->order->user->name : $item->order->customer_name }}
                                    @if(!$item->order->user)
                                        <small class="text-muted">(Khách)</small>
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->product_price) }}₫</td>
                                <td>{{ number_format($item->quantity * $item->product_price) }}₫</td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->order->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($item->order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($product->orderItems()->count() > 10)
                <div class="text-center">
                    <small class="text-muted">Chỉ hiển thị 10 đơn hàng gần nhất</small>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Giá bán</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Giá gốc:</span>
                    <span class="fw-bold">{{ number_format($product->price) }}₫</span>
                </div>

                @if($product->sale_price)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Giá khuyến mãi:</span>
                    <span class="fw-bold text-danger">{{ number_format($product->sale_price) }}₫</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Giảm giá:</span>
                    <span class="badge bg-success">
                        {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                    </span>
                </div>
                @endif

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <span>Giá hiển thị:</span>
                    <span class="fw-bold text-primary fs-5">
                        {{ number_format($product->sale_price ?: $product->price) }}₫
                    </span>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Tồn kho</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Số lượng hiện tại:</span>
                    <span class="fw-bold fs-4 text-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                        {{ $product->stock }}
                    </span>
                </div>

                @if($product->stock == 0)
                <div class="alert alert-danger py-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>Hết hàng
                </div>
                @elseif($product->stock <= 10)
                <div class="alert alert-warning py-2">
                    <i class="fas fa-exclamation-circle me-2"></i>Sắp hết hàng
                </div>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thống kê bán hàng</h6>
            </div>
            <div class="card-body">
                @php
                $totalSold = $product->orderItems()->sum('quantity');
                $totalRevenue = $product->orderItems()->sum(\DB::raw('quantity * product_price'));
                $orderCount = $product->orderItems()->distinct('order_id')->count();
                @endphp

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Tổng bán:</span>
                    <span class="fw-bold">{{ $totalSold }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Số đơn hàng:</span>
                    <span class="fw-bold">{{ $orderCount }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span>Doanh thu:</span>
                    <span class="fw-bold text-success">{{ number_format($totalRevenue) }}₫</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Thao tác nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(!$product->status)
                    <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-play me-2"></i>Kích hoạt
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-pause me-2"></i>Tạm ngưng
                        </button>
                    </form>
                    @endif

                    @if(!$product->featured)
                    <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="featured" value="1">
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-star me-2"></i>Đánh dấu nổi bật
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="featured" value="0">
                        <button type="submit" class="btn btn-outline-info w-100">
                            <i class="far fa-star me-2"></i>Bỏ nổi bật
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<style>
.thumbnail-image {
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.3s;
}

.thumbnail-image:hover {
    border-color: #007bff;
}

.active-thumb {
    border-color: #007bff !important;
}
</style>

<script>
function confirmDelete() {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?\n\nLưu ý: Hành động này không thể hoàn tác!')) {
        document.getElementById('deleteForm').submit();
    }
}

function changeMainImage(imageUrl, altText, thumbElement) {
    // Update main image
    const mainImage = document.getElementById('mainImage');
    if (mainImage) {
        mainImage.src = imageUrl;
        mainImage.alt = altText;
    }

    // Update active thumbnail
    document.querySelectorAll('.thumbnail-image').forEach(thumb => {
        thumb.classList.remove('active-thumb');
    });
    thumbElement.classList.add('active-thumb');
}
</script>
@endpush
