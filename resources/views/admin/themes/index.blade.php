@extends('admin.layouts.app')

@section('title', 'Quản lý Theme')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Theme</h1>
        <a href="{{ route('admin.themes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Theme Mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {!! session('info') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        @forelse($themes as $theme)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow h-100 {{ $theme->is_active ? 'border-success' : '' }}">
                    @if($theme->thumbnail)
                        <img src="{{ asset($theme->thumbnail) }}" 
                             class="card-img-top" 
                             alt="{{ $theme->name }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                             style="height: 200px;">
                            <i class="fas fa-image fa-4x text-white"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $theme->name }}</h5>
                            @if($theme->is_active)
                                <span class="badge badge-success">Đang dùng</span>
                            @elseif($theme->is_default)
                                <span class="badge badge-info">Mặc định</span>
                            @endif
                        </div>
                        
                        <p class="card-text text-muted small">
                            {{ $theme->description ?: 'Không có mô tả' }}
                        </p>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> {{ $theme->author ?: 'N/A' }}
                                &nbsp;|&nbsp;
                                <i class="fas fa-code-branch"></i> v{{ $theme->version }}
                            </small>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted"><i class="fas fa-folder"></i> {{ $theme->view_path }}</small>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white">
                        <div class="btn-group btn-group-sm w-100" role="group">
                            @if(!$theme->is_active)
                                <form action="{{ route('admin.themes.activate', $theme->id) }}" 
                                      method="POST" 
                                      class="flex-fill">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-success w-100"
                                            onclick="return confirm('Bạn có chắc muốn kích hoạt theme này?')">
                                        <i class="fas fa-check"></i> Kích hoạt
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('admin.themes.preview', $theme->id) }}" 
                               class="btn btn-info" 
                               target="_blank"
                               title="Xem trước">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <a href="{{ route('admin.themes.edit', $theme->id) }}" 
                               class="btn btn-primary" 
                               title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            @if(!$theme->is_active && !$theme->is_default)
                                <form action="{{ route('admin.themes.destroy', $theme->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Bạn có chắc muốn xóa theme này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <p class="mb-0">Chưa có theme nào. <a href="{{ route('admin.themes.create') }}">Tạo theme đầu tiên</a></p>
                </div>
            </div>
        @endforelse
    </div>

    @if($themes->count() > 0)
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle"></i> Hướng dẫn
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li><strong>Kích hoạt:</strong> Chọn theme để áp dụng cho website</li>
                    <li><strong>Xem trước:</strong> Xem theme trước khi kích hoạt</li>
                    <li><strong>Theme mặc định:</strong> Theme dự phòng khi không có theme nào được kích hoạt</li>
                    <li><strong>View Path:</strong> Đường dẫn đến file blade template (vd: themes.default.home)</li>
                </ul>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-2px);
}
</style>
@endpush
@endsection
