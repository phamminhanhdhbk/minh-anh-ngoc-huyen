@extends('admin.layouts.app')

@section('title', 'Quản lý Bình luận')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Quản lý Bình luận</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Tổng bình luận</h6>
                    <h3>{{ $totalComments }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Chờ duyệt</h6>
                    <h3>{{ $pendingComments }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Đã duyệt</h6>
                    <h3>{{ $approvedComments }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6>Bị từ chối</h6>
                    <h3>{{ $rejectedComments }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.post-comments.index') }}" method="GET" class="form-inline">
                <select name="status" class="form-control mr-2">
                    <option value="">-- Trạng thái --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                </select>
                <select name="post" class="form-control mr-2">
                    <option value="">-- Bài viết --</option>
                    @foreach($posts as $post)
                        <option value="{{ $post->id }}" {{ request('post') == $post->id ? 'selected' : '' }}>
                            {{ $post->title }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Lọc
                </button>
                <a href="{{ route('admin.post-comments.index') }}" class="btn btn-secondary">Làm mới</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th width="80">ID</th>
                            <th>Bài viết</th>
                            <th>Người gửi</th>
                            <th>Nội dung</th>
                            <th width="100">Trạng thái</th>
                            <th width="120">Ngày gửi</th>
                            <th width="180">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                            <tr>
                                <td>{{ $comment->id }}</td>
                                <td>
                                    <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank">
                                        {{ Str::limit($comment->post->title, 40) }}
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $comment->name }}</strong><br>
                                    <small>{{ $comment->email }}</small>
                                </td>
                                <td>{{ Str::limit($comment->content, 80) }}</td>
                                <td>
                                    @if($comment->status == 'pending')
                                        <span class="badge badge-warning">Chờ duyệt</span>
                                    @elseif($comment->status == 'approved')
                                        <span class="badge badge-success">Đã duyệt</span>
                                    @else
                                        <span class="badge badge-danger">Từ chối</span>
                                    @endif
                                </td>
                                <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($comment->status != 'approved')
                                        <form action="{{ route('admin.post-comments.approve', $comment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Duyệt">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($comment->status != 'rejected')
                                        <form action="{{ route('admin.post-comments.reject', $comment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning" title="Từ chối">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.post-comments.destroy', $comment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa bình luận?')">
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
                                <td colspan="7" class="text-center">Chưa có bình luận nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $comments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
