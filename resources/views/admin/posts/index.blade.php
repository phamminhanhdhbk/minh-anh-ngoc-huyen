@extends('admin.layouts.app')

@section('title', 'Quản lý Bài viết')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Quản lý Bài viết</h2>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Bài viết
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Tổng bài viết</h6>
                            <h3 class="mb-0 mt-2">{{ $totalPosts }}</h3>
                        </div>
                        <i class="fas fa-file-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Đã xuất bản</h6>
                            <h3 class="mb-0 mt-2">{{ $publishedPosts }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Bản nháp</h6>
                            <h3 class="mb-0 mt-2">{{ $draftPosts }}</h3>
                        </div>
                        <i class="fas fa-edit fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Đã lưu trữ</h6>
                            <h3 class="mb-0 mt-2">{{ $archivedPosts }}</h3>
                        </div>
                        <i class="fas fa-archive fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.posts.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-2 mb-2">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="status" class="form-control">
                        <option value="">-- Trạng thái --</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="category" class="form-control">
                        <option value="">-- Danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mr-2 mb-2">
                    <i class="fas fa-search"></i> Lọc
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary mb-2">
                    <i class="fas fa-redo"></i> Làm mới
                </a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="80">ID</th>
                            <th width="100">Ảnh</th>
                            <th>Tiêu đề</th>
                            <th width="150">Danh mục</th>
                            <th width="120">Tác giả</th>
                            <th width="100">Lượt xem</th>
                            <th width="100">Trạng thái</th>
                            <th width="120">Ngày xuất bản</th>
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>
                                    @if($post->featured_image)
                                        <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $post->title }}</strong>
                                    @if($post->featured)
                                        <span class="badge badge-warning ml-1"><i class="fas fa-star"></i> Nổi bật</span>
                                    @endif
                                    @if($post->excerpt)
                                        <br><small class="text-muted">{{ Str::limit($post->excerpt, 80) }}</small>
                                    @endif
                                </td>
                                <td>{{ $post->category->name ?? 'N/A' }}</td>
                                <td>{{ $post->author->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-primary" style="background-color: #007bff !important; color: white !important;">{{ $post->views }}</span>
                                </td>
                                <td>
                                    @if($post->status == 'published')
                                        <span class="badge badge-success" style="background-color: #28a745 !important; color: white !important;">Đã xuất bản</span>
                                    @elseif($post->status == 'draft')
                                        <span class="badge badge-warning" style="background-color: #ffc107 !important; color: black !important;">Bản nháp</span>
                                    @else
                                        <span class="badge badge-danger" style="background-color: #dc3545 !important; color: white !important;">Lưu trữ</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post->published_at)
                                        {{ $post->published_at->format('d/m/Y') }}
                                        <br><small class="text-muted">{{ $post->published_at->format('H:i') }}</small>
                                    @else
                                        <span class="text-muted">--</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-sm btn-secondary" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-info" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Chưa có bài viết nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $posts->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
