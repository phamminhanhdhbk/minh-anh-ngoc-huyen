@extends('layouts.app')

@section('title', $post->title . ' - Blog')

@section('content')
<style>
    .blog-post {
        max-width: 100%;
        overflow-x: hidden;
    }
    
    .post-content {
        max-width: 100%;
        overflow-x: auto;
    }
    
    .post-content img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 1rem 0;
    }
    
    .post-content table {
        max-width: 100%;
        overflow-x: auto;
    }
    
    .post-content p {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    
    /* Ensure columns don't overflow */
    .col-lg-8,
    .col-lg-4 {
        max-width: 100%;
        overflow-x: hidden;
    }
    
    /* Featured image wrapper */
    .featured-image-wrapper {
        width: 100%;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .featured-image-wrapper img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        display: block;
    }
</style>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Post Header -->
            <article class="blog-post">
                <h1 class="mb-3">{{ $post->title }}</h1>

                <div class="text-muted mb-4">
                    <i class="fas fa-user"></i> {{ $post->author->name ?? 'Admin' }} |
                    <i class="fas fa-calendar"></i> {{ $post->published_at->format('d/m/Y H:i') }} |
                    <i class="fas fa-eye"></i> {{ $post->views }} lượt xem |
                    <i class="fas fa-folder"></i> <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}">{{ $post->category->name }}</a>
                </div>

                <!-- Featured Image -->
                @if($post->featured_image && $post->featured_image_visible)
                    <div class="featured-image-wrapper">
                        <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" 
                             class="rounded shadow">
                    </div>
                @endif

                <!-- Excerpt -->
                @if($post->excerpt)
                    <div class="alert alert-light">
                        <strong>{{ $post->excerpt }}</strong>
                    </div>
                @endif

                <!-- Content -->
                <div class="post-content">
                    {!! sanitizeHtml($post->content) !!}
                </div>

                <!-- Tags -->
                @if($post->tags->count() > 0)
                    <div class="mt-4 pt-3 border-top">
                        <strong>Tags:</strong>
                        @foreach($post->tags as $tag)
                            <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="badge badge-secondary ml-1">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Share Buttons -->
                <div class="mt-4 pt-3 border-top">
                    <strong>Chia sẻ:</strong>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" class="btn btn-sm btn-primary ml-2">
                        <i class="fab fa-facebook"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-sm btn-info ml-2">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                </div>
            </article>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
                <div class="mt-5">
                    <h4 class="mb-3">Bài viết liên quan</h4>
                    <div class="row">
                        @foreach($relatedPosts as $related)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    @if($related->featured_image && $related->featured_image_visible)
                                        <img src="{{ asset($related->featured_image) }}" class="card-img-top" alt="{{ $related->title }}" style="height: 150px; object-fit: cover;">
                                    @endif
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="{{ route('blog.show', $related->slug) }}">{{ $related->title }}</a>
                                        </h6>
                                        <small class="text-muted">{{ $related->published_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Comments Section -->
            <div class="mt-5">
                <h4 class="mb-4">Bình luận ({{ $comments->count() }})</h4>

                <!-- Comment Form -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Để lại bình luận</h5>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('blog.comment', $post->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">Nội dung <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-comment"></i> Gửi bình luận
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Comments List -->
                @foreach($comments as $comment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $comment->name }}</strong>
                                    @if($comment->user_id)
                                        <span class="badge badge-success ml-1">Thành viên</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mt-2 mb-0">{{ $comment->content }}</p>

                            <!-- Replies -->
                            @if($comment->replies->count() > 0)
                                <div class="ml-4 mt-3">
                                    @foreach($comment->replies as $reply)
                                        <div class="card bg-light mb-2">
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between">
                                                    <strong>{{ $reply->name }}</strong>
                                                    <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-0 mt-1">{{ $reply->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Categories -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Danh mục</h5>
                    <div class="list-group list-group-flush">
                        @foreach($categories as $category)
                            <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="list-group-item list-group-item-action">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Popular Posts -->
            @if($popularPosts->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bài viết phổ biến</h5>
                        <div class="list-group list-group-flush">
                            @foreach($popularPosts as $popular)
                                <a href="{{ route('blog.show', $popular->slug) }}" class="list-group-item list-group-item-action">
                                    <strong>{{ Str::limit($popular->title, 50) }}</strong>
                                    <br><small class="text-muted">{{ $popular->views }} lượt xem</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
