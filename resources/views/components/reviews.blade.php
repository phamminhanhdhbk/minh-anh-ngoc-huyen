@php
$averageRating = $product->average_rating ?? 0;
$reviewsCount = $product->reviews_count ?? 0;
$distribution = $product->rating_distribution ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
$userReview = auth()->check() ? $product->reviews()->where('user_id', auth()->id())->first() : null;
$canReview = auth()->check() && !$userReview;
@endphp

<div class="reviews-section mt-5">
    <div class="row">
        <div class="col-md-4">
            <!-- Rating Summary -->
            <div class="rating-summary text-center p-4 bg-light rounded">
                <h3 class="display-4 fw-bold text-primary">{{ number_format($averageRating, 1) }}</h3>
                <div class="mb-2">{!! $product->stars_html !!}</div>
                <p class="text-muted">{{ $reviewsCount }} đánh giá</p>

                @if($reviewsCount > 0)
                <!-- Rating Distribution -->
                <div class="rating-distribution mt-4">
                    @for($i = 5; $i >= 1; $i--)
                    <div class="d-flex align-items-center mb-1">
                        <span class="me-2">{{ $i }} <i class="fas fa-star text-warning"></i></span>
                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                            <div class="progress-bar bg-warning"
                                 style="width: {{ $reviewsCount > 0 ? ($distribution[$i] / $reviewsCount * 100) : 0 }}%"></div>
                        </div>
                        <small class="text-muted">{{ $distribution[$i] }}</small>
                    </div>
                    @endfor
                </div>
                @endif
            </div>
        </div>

        <div class="col-md-8">
            @if($canReview)
            <!-- Review Form -->
            <div class="review-form mb-4">
                <h5>Viết đánh giá</h5>
                <form action="{{ route('reviews.store', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Rating -->
                    <div class="mb-3">
                        <label class="form-label">Đánh giá sao</label>
                        <div class="rating-input">
                            @for($i = 1; $i <= 5; $i++)
                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                            <label for="star{{ $i }}" class="star-label">
                                <i class="fas fa-star"></i>
                            </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề (tùy chọn)</label>
                        <input type="text" name="title" class="form-control" placeholder="Tóm tắt đánh giá của bạn">
                    </div>

                    <!-- Comment -->
                    <div class="mb-3">
                        <label class="form-label">Nội dung đánh giá</label>
                        <textarea name="comment" class="form-control" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                    </div>

                    <!-- Images -->
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh (tùy chọn)</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                        <div class="form-text">Tối đa 5 hình ảnh, mỗi ảnh không quá 2MB</div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Gửi đánh giá
                    </button>
                </form>
            </div>
            @elseif($userReview)
            <!-- User's Review -->
            <div class="user-review mb-4 p-3 border rounded">
                <h5>Đánh giá của bạn</h5>
                <div class="d-flex justify-content-between">
                    <div>
                        {!! $userReview->stars_html !!}
                        @if($userReview->verified_purchase)
                        <span class="badge bg-success ms-2">Đã mua hàng</span>
                        @endif
                        @if(!$userReview->approved)
                        <span class="badge bg-warning ms-2">Chờ duyệt</span>
                        @endif
                    </div>
                    <small class="text-muted">{{ $userReview->created_at->diffForHumans() }}</small>
                </div>

                @if($userReview->title)
                <h6 class="mt-2">{{ $userReview->title }}</h6>
                @endif

                @if($userReview->comment)
                <p class="mt-2">{{ $userReview->comment }}</p>
                @endif

                @if($userReview->images)
                <div class="review-images mt-2">
                    @foreach($userReview->image_urls as $image)
                    <img src="{{ $image }}" class="img-thumbnail me-2" style="width: 60px; height: 60px; object-fit: cover;">
                    @endforeach
                </div>
                @endif

                <div class="mt-2">
                    <button class="btn btn-sm btn-outline-primary" onclick="editReview({{ $userReview->id }})">
                        <i class="fas fa-edit me-1"></i>Chỉnh sửa
                    </button>
                    <button class="btn btn-sm btn-outline-danger ms-2" onclick="deleteReview({{ $userReview->id }})">
                        <i class="fas fa-trash me-1"></i>Xóa
                    </button>
                </div>
            </div>
            @elseif(!auth()->check())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <a href="{{ route('login') }}" class="alert-link">Đăng nhập</a> để viết đánh giá sản phẩm này.
            </div>
            @endif

            <!-- Reviews List -->
            <div class="reviews-list">
                <h5>Đánh giá từ khách hàng</h5>
                <div id="reviews-container">
                    @include('components.review-list', ['reviews' => $product->approvedReviews()->with('user')->latest()->paginate(5)])
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input[type="radio"] {
    display: none;
}

.star-label {
    color: #ddd;
    font-size: 1.5rem;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-input input[type="radio"]:checked ~ .star-label,
.rating-input .star-label:hover,
.rating-input .star-label:hover ~ .star-label {
    color: #ffc107;
}

.review-images img {
    cursor: pointer;
    transition: transform 0.2s;
}

.review-images img:hover {
    transform: scale(1.1);
}
</style>
@endpush

@push('scripts')
<script>
function editReview(reviewId) {
    // Implementation for editing review
    alert('Chức năng chỉnh sửa đang được phát triển...');
}

function deleteReview(reviewId) {
    if (confirm('Bạn có chắc muốn xóa đánh giá này?')) {
        fetch(`/reviews/${reviewId}`, {
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

// Load more reviews
function loadMoreReviews(page) {
    fetch(`{{ route('reviews.product', $product) }}?page=${page}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('reviews-container').innerHTML = html;
    });
}
</script>
@endpush
