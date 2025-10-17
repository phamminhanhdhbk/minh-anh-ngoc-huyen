@extends('admin.layouts.app')

@section('title', 'Chi tiết đánh giá')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Chi tiết đánh giá</h1>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Review Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đánh giá</h6>
                </div>
                <div class="card-body">
                    <!-- Rating -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center">
                            {!! $review->stars_html !!}
                            <span class="h4 ms-3 mb-0">{{ $review->rating }}/5</span>
                            <div class="ms-auto">
                                @if($review->approved)
                                <span class="badge bg-success">Đã duyệt</span>
                                @else
                                <span class="badge bg-warning">Chờ duyệt</span>
                                @endif

                                @if($review->verified_purchase)
                                <span class="badge bg-info">Đã mua hàng</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Title -->
                    @if($review->title)
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Tiêu đề:</label>
                        <h5>{{ $review->title }}</h5>
                    </div>
                    @endif

                    <!-- Comment -->
                    @if($review->comment)
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Nội dung:</label>
                        <p class="border rounded p-3 bg-light">{{ $review->comment }}</p>
                    </div>
                    @endif

                    <!-- Images -->
                    @if($review->images)
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Hình ảnh:</label>
                        <div class="row">
                            @foreach($review->image_urls as $image)
                            <div class="col-md-3 mb-3">
                                <img src="{{ $image }}"
                                     class="img-fluid rounded border"
                                     style="cursor: pointer; width: 100%; height: 150px; object-fit: cover;"
                                     onclick="openImageModal('{{ $image }}')">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Ngày tạo:</strong> {{ $review->created_at->format('d/m/Y H:i:s') }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Cập nhật:</strong> {{ $review->updated_at->format('d/m/Y H:i:s') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Customer Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khách hàng</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($review->user->avatar)
                        <img src="{{ asset('storage/' . $review->user->avatar) }}"
                             class="rounded-circle me-3"
                             style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                             style="width: 50px; height: 50px;">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        @endif
                        <div>
                            <h6 class="mb-1">{{ $review->user->name }}</h6>
                            <small class="text-muted">{{ $review->user->email }}</small>
                        </div>
                    </div>

                    <div class="mb-2">
                        <strong>Ngày đăng ký:</strong><br>
                        <small>{{ $review->user->created_at->format('d/m/Y') }}</small>
                    </div>

                    @if($review->user->phone)
                    <div class="mb-2">
                        <strong>Điện thoại:</strong><br>
                        <small>{{ $review->user->phone }}</small>
                    </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('admin.users.show', $review->user) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-user me-1"></i>Xem hồ sơ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sản phẩm</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        @if($review->product->image)
                        <img src="{{ asset('storage/' . $review->product->image) }}"
                             class="img-thumbnail me-3"
                             style="width: 80px; height: 80px; object-fit: cover;">
                        @endif
                        <div>
                            <h6>{{ $review->product->name }}</h6>
                            <p class="text-muted mb-1">{{ number_format($review->product->price) }}đ</p>
                            <small class="badge bg-secondary">{{ $review->product->category->name }}</small>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.products.show', $review->product) }}" class="btn btn-sm btn-info me-2">
                            <i class="fas fa-box me-1"></i>Xem sản phẩm
                        </a>
                        <a href="{{ route('products.show', $review->product) }}" class="btn btn-sm btn-secondary" target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i>Xem công khai
                        </a>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hành động</h6>
                </div>
                <div class="card-body">
                    @if(!$review->approved)
                    <button onclick="approveReview({{ $review->id }})" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-check me-2"></i>Duyệt đánh giá
                    </button>
                    @else
                    <button onclick="rejectReview({{ $review->id }})" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-times me-2"></i>Bỏ duyệt
                    </button>
                    @endif

                    <button onclick="deleteReview({{ $review->id }})" class="btn btn-danger btn-block">
                        <i class="fas fa-trash me-2"></i>Xóa đánh giá
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hình ảnh đánh giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

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
    if (confirm('Bạn có chắc muốn bỏ duyệt đánh giá này?')) {
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
                window.location.href = '/admin/reviews';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}
</script>
@endpush
