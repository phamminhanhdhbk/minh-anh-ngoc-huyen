@if($reviews->count() > 0)
    @foreach($reviews as $review)
    <div class="review-item border-bottom py-3 mb-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="d-flex align-items-center">
                <div class="user-avatar me-3">
                    @if($review->user->avatar)
                    <img src="{{ asset('storage/' . $review->user->avatar) }}"
                         class="rounded-circle"
                         style="width: 40px; height: 40px; object-fit: cover;">
                    @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                         style="width: 40px; height: 40px;">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    @endif
                </div>
                <div>
                    <h6 class="mb-1">{{ $review->user->name }}</h6>
                    <div class="d-flex align-items-center">
                        {!! $review->stars_html !!}
                        @if($review->verified_purchase)
                        <span class="badge bg-success ms-2 small">Đã mua hàng</span>
                        @endif
                    </div>
                </div>
            </div>
            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
        </div>

        @if($review->title)
        <h6 class="review-title">{{ $review->title }}</h6>
        @endif

        @if($review->comment)
        <p class="review-comment mb-2">{{ $review->comment }}</p>
        @endif

        @if($review->images)
        <div class="review-images mb-2">
            @foreach($review->image_urls as $index => $image)
            <img src="{{ $image }}"
                 class="img-thumbnail me-2 mb-2 review-image"
                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                 onclick="openImageModal('{{ $image }}', {{ $index }}, {{ json_encode($review->image_urls) }})">
            @endforeach
        </div>
        @endif

        <!-- Review Actions -->
        <div class="review-actions">
            <button class="btn btn-sm btn-outline-secondary me-2"
                    onclick="toggleHelpful({{ $review->id }})"
                    data-review-id="{{ $review->id }}">
                <i class="fas fa-thumbs-up me-1"></i>
                <span class="helpful-text">Hữu ích</span>
                <span class="helpful-count">({{ $review->helpful_count ?? 0 }})</span>
            </button>

            @auth
            @if(auth()->id() !== $review->user_id)
            <button class="btn btn-sm btn-outline-secondary"
                    onclick="reportReview({{ $review->id }})">
                <i class="fas fa-flag me-1"></i>Báo cáo
            </button>
            @endif
            @endauth
        </div>
    </div>
    @endforeach

    <!-- Pagination -->
    @if($reviews->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($reviews->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">‹</span></li>
                @else
                    <li class="page-item"><button class="page-link" onclick="loadMoreReviews({{ $reviews->currentPage() - 1 }})">‹</button></li>
                @endif

                {{-- Pagination Elements --}}
                @for($i = 1; $i <= $reviews->lastPage(); $i++)
                    @if($i == $reviews->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                    @else
                        <li class="page-item"><button class="page-link" onclick="loadMoreReviews({{ $i }})">{{ $i }}</button></li>
                    @endif
                @endfor

                {{-- Next Page Link --}}
                @if ($reviews->hasMorePages())
                    <li class="page-item"><button class="page-link" onclick="loadMoreReviews({{ $reviews->currentPage() + 1 }})">›</button></li>
                @else
                    <li class="page-item disabled"><span class="page-link">›</span></li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="fas fa-star text-muted" style="font-size: 3rem;"></i>
        <h5 class="mt-3 text-muted">Chưa có đánh giá nào</h5>
        <p class="text-muted">Hãy là người đầu tiên đánh giá sản phẩm này!</p>
    </div>
@endif

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
                <div class="mt-3">
                    <button class="btn btn-outline-secondary me-2" onclick="previousImage()">
                        <i class="fas fa-chevron-left"></i> Trước
                    </button>
                    <span id="imageCounter"></span>
                    <button class="btn btn-outline-secondary ms-2" onclick="nextImage()">
                        Sau <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentImageIndex = 0;
let currentImages = [];

function openImageModal(imageSrc, index, images) {
    currentImageIndex = index;
    currentImages = images;

    document.getElementById('modalImage').src = imageSrc;
    updateImageCounter();

    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

function updateImageCounter() {
    document.getElementById('imageCounter').textContent =
        `${currentImageIndex + 1} / ${currentImages.length}`;
}

function previousImage() {
    if (currentImageIndex > 0) {
        currentImageIndex--;
        document.getElementById('modalImage').src = currentImages[currentImageIndex];
        updateImageCounter();
    }
}

function nextImage() {
    if (currentImageIndex < currentImages.length - 1) {
        currentImageIndex++;
        document.getElementById('modalImage').src = currentImages[currentImageIndex];
        updateImageCounter();
    }
}

function toggleHelpful(reviewId) {
    @auth
    fetch(`/reviews/${reviewId}/helpful`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = document.querySelector(`[data-review-id="${reviewId}"]`);
            const countSpan = button.querySelector('.helpful-count');
            const textSpan = button.querySelector('.helpful-text');

            countSpan.textContent = `(${data.count})`;
            textSpan.textContent = data.helpful ? 'Đã hữu ích' : 'Hữu ích';

            if (data.helpful) {
                button.classList.remove('btn-outline-secondary');
                button.classList.add('btn-secondary');
            } else {
                button.classList.remove('btn-secondary');
                button.classList.add('btn-outline-secondary');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
    @else
    alert('Vui lòng đăng nhập để thực hiện hành động này.');
    @endauth
}

function reportReview(reviewId) {
    @auth
    if (confirm('Bạn có chắc muốn báo cáo đánh giá này?')) {
        fetch(`/reviews/${reviewId}/report`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Đã gửi báo cáo thành công. Chúng tôi sẽ xem xét sớm nhất.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    @else
    alert('Vui lòng đăng nhập để thực hiện hành động này.');
    @endauth
}
</script>
@endpush
