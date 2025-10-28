@extends('admin.layouts.app')

@section('title', 'Quản lý Tags')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Quản lý Tags</h2>
                <a href="{{ route('admin.post-tags.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Tag
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="80">ID</th>
                            <th>Tên Tag</th>
                            <th>Slug</th>
                            <th width="120">Số bài viết</th>
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            <tr>
                                <td>{{ $tag->id }}</td>
                                <td><strong>{{ $tag->name }}</strong></td>
                                <td><code>{{ $tag->slug }}</code></td>
                                <td class="text-center">
                                    <span class="badge badge-primary" style="background-color: #007bff !important; color: white !important;">{{ $tag->posts_count }} bài</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.post-tags.edit', $tag->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.post-tags.destroy', $tag->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tag này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Chưa có tag nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $tags->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
