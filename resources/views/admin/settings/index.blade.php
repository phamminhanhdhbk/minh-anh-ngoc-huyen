@extends('admin.layouts.app')

@section('title', 'Cấu hình trang web - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-cog me-2"></i>Cấu hình trang web
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.settings.edit') }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Chỉnh sửa
            </a>
        </div>
        <div class="btn-group">
            <form action="{{ route('admin.settings.reset') }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn khôi phục cấu hình mặc định?')">
                @csrf
                <button type="submit" class="btn btn-outline-warning">
                    <i class="fas fa-undo me-2"></i>Khôi phục mặc định
                </button>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($settings->count() > 0)
<div class="row">
    <div class="col-lg-3">
        <!-- Settings Navigation -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Nhóm cấu hình</h6>
            </div>
            <div class="list-group list-group-flush">
                @foreach($settings as $group => $groupSettings)
                <a href="#{{ $group }}" class="list-group-item list-group-item-action"
                   onclick="showGroup('{{ $group }}')">
                    <i class="fas fa-{{ getGroupIcon($group) }} me-2"></i>
                    {{ getGroupName($group) }}
                    <span class="badge bg-secondary ms-auto">{{ $groupSettings->count() }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        @foreach($settings as $group => $groupSettings)
        <div class="card mb-4 group-section" id="section-{{ $group }}" style="{{ $loop->first ? '' : 'display: none;' }}">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-{{ getGroupIcon($group) }} me-2"></i>
                    {{ getGroupName($group) }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($groupSettings as $setting)
                    <div class="col-md-6 mb-4">
                        <div class="setting-item border rounded p-3 h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <label class="fw-bold text-primary">{{ $setting->label }}</label>
                                <span class="badge bg-light text-dark">{{ ucfirst($setting->type) }}</span>
                            </div>

                            @if($setting->description)
                            <p class="text-muted small mb-3">{{ $setting->description }}</p>
                            @endif

                            <div class="setting-value">
                                @if($setting->type === 'image')
                                    @if($setting->value)
                                    <img src="{{ $setting->formatted_value }}" alt="{{ $setting->label }}"
                                         class="img-thumbnail" style="max-height: 100px;">
                                    @else
                                    <div class="bg-light rounded p-3 text-center text-muted">
                                        <i class="fas fa-image fa-2x mb-2"></i>
                                        <p class="mb-0">Chưa có hình ảnh</p>
                                    </div>
                                    @endif
                                @elseif($setting->type === 'boolean')
                                    <span class="badge bg-{{ $setting->value ? 'success' : 'secondary' }} fs-6">
                                        <i class="fas fa-{{ $setting->value ? 'check' : 'times' }} me-1"></i>
                                        {{ $setting->formatted_value }}
                                    </span>
                                @elseif($setting->type === 'textarea')
                                    <div class="bg-light p-2 rounded">
                                        {!! nl2br(e($setting->value)) !!}
                                    </div>
                                @elseif($setting->type === 'url')
                                    @if($setting->value)
                                    <a href="{{ $setting->value }}" target="_blank" class="text-decoration-none">
                                        {{ $setting->value }} <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                    @else
                                    <span class="text-muted">Chưa cấu hình</span>
                                    @endif
                                @else
                                    <span class="fw-semibold">{{ $setting->value ?: 'Chưa cấu hình' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="text-center py-5">
    <i class="fas fa-cog fa-3x text-muted mb-3"></i>
    <h4>Chưa có cấu hình nào</h4>
    <p class="text-muted">Hãy tạo cấu hình mặc định để bắt đầu.</p>
    <form action="{{ route('admin.settings.reset') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tạo cấu hình mặc định
        </button>
    </form>
</div>
@endif
@endsection

@push('scripts')
<script>
function showGroup(group) {
    // Hide all sections
    document.querySelectorAll('.group-section').forEach(section => {
        section.style.display = 'none';
    });

    // Show selected section
    document.getElementById('section-' + group).style.display = 'block';

    // Update active nav item
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.classList.remove('active');
    });
    event.target.classList.add('active');
}
</script>
@endpush

@php
function getGroupIcon($group) {
    $icons = [
        'general' => 'home',
        'contact' => 'phone',
        'social' => 'share-alt',
        'business' => 'business-time'
    ];
    return $icons[$group] ?? 'cog';
}

function getGroupName($group) {
    $names = [
        'general' => 'Thông tin chung',
        'contact' => 'Thông tin liên hệ',
        'social' => 'Mạng xã hội',
        'business' => 'Kinh doanh'
    ];
    return $names[$group] ?? ucfirst($group);
}
@endphp
