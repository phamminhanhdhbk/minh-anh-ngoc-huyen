@extends('layouts.app')

@section('title', 'Sản phẩm - Shop VO')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Bộ lọc</h5>
                </div>
                <div class="card-body">
                    <!-- Search -->
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="mb-3">
                            <label class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Nhập tên sản phẩm...">
                        </div>

                        <!-- Categories -->
                        <div class="mb-3">
                            <label class="form-label">Danh mục</label>
                            <div class="list-group list-group-flush">
                                <a href="{{ route('products.index') }}"
                                   class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                                    Tất cả
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                       class="list-group-item list-group-item-action {{ request('category') == $category->slug ? 'active' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Tìm kiếm
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    @if(request('search'))
                        Kết quả tìm kiếm: "{{ request('search') }}"
                    @elseif(request('category'))
                        {{ $categories->where('slug', request('category'))->first()->name ?? 'Sản phẩm' }}
                    @else
                        Tất cả sản phẩm
                    @endif
                </h2>
                <span class="text-muted">{{ $products->total() }} sản phẩm</span>
            </div>

            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card product-card border-0 shadow-sm h-100">
                            @if($product->image)
                                <img src="{{ $product->image }}" class="card-img-top product-image" alt="{{ $product->name }}">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center product-image">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <p class="text-muted small">{{ $product->category->name }}</p>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($product->description, 100) }}
                                </p>
                                <div class="mt-auto">
                                    @if($product->sale_price < $product->price)
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="text-decoration-line-through text-muted me-2">{{ number_format($product->price) }}đ</span>
                                            <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h6 text-primary mb-0">{{ number_format($product->sale_price) }}đ</span>
                                        @if($product->stock > 0)
                                            <button class="btn btn-primary btn-sm" onclick="addToCart({{ $product->id }})">
                                                <i class="fas fa-cart-plus me-1"></i>Mua
                                            </button>
                                        @else
                                            <span class="btn btn-secondary btn-sm disabled">Hết hàng</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">Còn lại: {{ $product->stock }} sản phẩm</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-5x text-muted mb-3"></i>
                    <h4>Không tìm thấy sản phẩm nào</h4>
                    <p class="text-muted">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Xem tất cả sản phẩm
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
