@extends('admin.layouts.app')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Quản lý đánh giá</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng đánh giá
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Chờ duyệt
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đã duyệt
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Đã mua hàng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['verified'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Phân bố đánh giá</h6>
                </div>
                <div class="card-body">
                    @foreach($ratingStats as $rating => $count)
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-3" style="width: 60px;">{{ $rating }} sao</span>
                        <div class="progress flex-grow-1 me-3" style="height: 20px;">
                            <div class="progress-bar bg-warning"
                                 style="width: {{ $stats['total'] > 0 ? ($count / $stats['total'] * 100) : 0 }}%">
                                {{ $count }}
                            </div>
                        </div>
                        <span class="text-muted">{{ number_format($stats['total'] > 0 ? ($count / $stats['total'] * 100) : 0, 1) }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bộ lọc</h6>
        </div>
        <div class="card-body">
            <form method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-control" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Đã mua hàng</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="rating" class="form-control" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tất cả đánh giá</option>
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Tìm kiếm đánh giá, sản phẩm, khách hàng..."
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đánh giá</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Đánh giá</th>
                            <th>Nội dung</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($review->user->avatar)
                                    <img src="{{ asset('storage/' . $review->user->avatar) }}"
                                         class="rounded-circle me-2"
                                         style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                         style="width: 32px; height: 32px; font-size: 12px;">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                    @endif
                                    <div>
                                        <div class="font-weight-bold">{{ $review->user->name }}</div>
                                        <small class="text-muted">{{ $review->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('products.show', $review->product) }}" target="_blank">
                                    {{ Str::limit($review->product->name, 30) }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {!! $review->stars_html !!}
                                    <span class="ms-2 badge bg-warning">{{ $review->rating }}</span>
                                </div>
                            </td>
                            <td>
                                @if($review->title)
                                <strong>{{ Str::limit($review->title, 20) }}</strong><br>
                                @endif
                                @if($review->comment)
                                <small>{{ Str::limit($review->comment, 50) }}</small>
                                @endif
                                @if($review->images)
                                <br><small class="text-info"><i class="fas fa-images"></i> {{ count($review->image_urls) }} ảnh</small>
                                @endif
                            </td>
                            <td>
                                @if($review->approved)
                                <span class="badge bg-success">Đã duyệt</span>
                                @else
                                <span class="badge bg-warning">Chờ duyệt</span>
                                @endif

                                @if($review->verified_purchase)
                                <br><span class="badge bg-info mt-1">Đã mua</span>
                                @endif
                            </td>
                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.reviews.show', $review) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if(!$review->approved)
                                    <button onclick="approveReview({{ $review->id }})"
                                            class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @else
                                    <button onclick="rejectReview({{ $review->id }})"
                                            class="btn btn-sm btn-warning">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif

                                    <button onclick="deleteReview({{ $review->id }})"
                                            class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có đánh giá nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reviews->hasPages())
            <div class="d-flex justify-content-center">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function approveReview(reviewId) {
    if (confirm('Bạn có chắc muốn duyệt đánh giá này?')) {
        fetch(`/admin/reviews/${reviewId}/approve`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

function rejectReview(reviewId) {
    if (confirm('Bạn có chắc muốn từ chối đánh giá này?')) {
        fetch(`/admin/reviews/${reviewId}/reject`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

function deleteReview(reviewId) {
    if (confirm('Bạn có chắc muốn xóa đánh giá này? Hành động này không thể hoàn tác.')) {
        fetch(`/admin/reviews/${reviewId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}
</script>
@endpush
