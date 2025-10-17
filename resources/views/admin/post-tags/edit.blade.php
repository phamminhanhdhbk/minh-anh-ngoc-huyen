@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Tag')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Chỉnh sửa Tag</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.post-tags.update', $tag->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Tên Tag <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $tag->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                           id="slug" name="slug" value="{{ old('slug', $tag->slug) }}">
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="{{ route('admin.post-tags.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
@endsection
