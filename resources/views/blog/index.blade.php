@extends('layouts.app')

@section('title', 'Blog - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-4">
                <i class="fas fa-blog"></i> Blog
            </h1>

            @if(request('search'))
                <div class="alert alert-info">
                    Kết quả tìm kiếm cho: "<strong>{{ request('search') }}</strong>"
                </div>
            @endif

            @if(request('category'))
                <div class="alert alert-info">
                    Danh mục: <strong>{{ request('category') }}</strong>
                </div>
            @endif

            <!-- Posts Grid -->
            <div class="row">
                @forelse($posts as $post)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($post->featured_image && $post->featured_image_visible)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif

                            <div class="card-body">
                                @if($post->featured)
                                    <span class="badge badge-warning mb-2">
                                        <i class="fas fa-star"></i> Nổi bật
                                    </span>
                                @endif

                                <h5 class="card-title">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-dark">
                                        {{ $post->title }}
                                    </a>
                                </h5>

                                <p class="card-text text-muted small">
                                    <i class="fas fa-user"></i> {{ $post->author->name ?? 'Admin' }} |
                                    <i class="fas fa-calendar"></i> {{ $post->published_at->format('d/m/Y') }} |
                                    <i class="fas fa-eye"></i> {{ $post->views }} lượt xem
                                </p>

                                @if($post->excerpt)
                                    <p class="card-text">{{ Str::limit($post->excerpt, 120) }}</p>
                                @endif

                                <div class="mt-2">
                                    <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="badge badge-primary">
                                        {{ $post->category->name }}
                                    </a>
                                </div>
                            </div>

                            <div class="card-footer bg-white">
                                <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-sm btn-outline-primary">
                                    Đọc tiếp <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Chưa có bài viết nào.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Tìm kiếm</h5>
                    <form action="{{ route('blog.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Categories -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Danh mục</h5>
                    <div class="list-group list-group-flush">
                        @foreach($categories as $category)
                            <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                {{ $category->name }}
                                <span class="badge badge-primary badge-pill">{{ $category->published_posts_count ?? 0 }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Featured Posts -->
            @if($featuredPosts->count() > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Bài viết nổi bật</h5>
                        <div class="list-group list-group-flush">
                            @foreach($featuredPosts as $featured)
                                <a href="{{ route('blog.show', $featured->slug) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex">
                                        @if($featured->featured_image && $featured->featured_image_visible)
                                            <img src="{{ asset('storage/' . $featured->featured_image) }}" alt="{{ $featured->title }}"
                                                 class="mr-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ Str::limit($featured->title, 50) }}</strong>
                                            <br><small class="text-muted">{{ $featured->published_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Popular Posts -->
            @if($popularPosts->count() > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Bài viết phổ biến</h5>
                        <div class="list-group list-group-flush">
                            @foreach($popularPosts as $popular)
                                <a href="{{ route('blog.show', $popular->slug) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ Str::limit($popular->title, 50) }}</strong>
                                            <br><small class="text-muted">{{ $popular->views }} lượt xem</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tags -->
            @if($tags->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tags</h5>
                        <div>
                            @foreach($tags as $tag)
                                <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="badge badge-secondary mb-1">
                                    {{ $tag->name }} ({{ $tag->posts_count }})
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
