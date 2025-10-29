@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa cấu hình trang web - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>Chỉnh sửa cấu hình trang web
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-3">
            <!-- Settings Navigation -->
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h6 class="mb-0">Nhóm cấu hình</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($settings as $group => $groupSettings)
                    <a href="#group-{{ $group }}" class="list-group-item list-group-item-action"
                       onclick="scrollToGroup('{{ $group }}')">
                        <i class="fas fa-{{ getGroupIcon($group) }} me-2"></i>
                        {{ getGroupName($group) }}
                        <span class="badge bg-secondary ms-auto">{{ $groupSettings->count() }}</span>
                    </a>
                    @endforeach
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            @foreach($settings as $group => $groupSettings)
            <div class="card mb-4" id="group-{{ $group }}">
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
                            <div class="form-group">
                                <label for="setting_{{ $setting->key }}" class="form-label fw-bold">
                                    {{ $setting->label }}
                                    @if($setting->description)
                                    <i class="fas fa-info-circle text-muted ms-1"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="{{ $setting->description }}"></i>
                                    @endif
                                </label>

                    @if($setting->type === 'text')
                    <input type="text"
                        class="form-control @error('settings.'.$setting->key) is-invalid @enderror"
                        id="setting_{{ $setting->key }}"
                        name="settings[{{ $setting->key }}]"
                        value="{{ old('settings.'.$setting->key, $setting->value) }}"
                        placeholder="Nhập {{ strtolower($setting->label) }}">

                    @elseif($setting->type === 'number')
                    <input type="number"
                        class="form-control @error('settings.'.$setting->key) is-invalid @enderror"
                        id="setting_{{ $setting->key }}"
                        name="settings[{{ $setting->key }}]"
                        value="{{ old('settings.'.$setting->key, $setting->value) }}"
                        min="0"
                        step="1"
                        placeholder="Nhập {{ strtolower($setting->label) }}">

                                @elseif($setting->type === 'textarea')
                                <textarea class="form-control @error('settings.'.$setting->key) is-invalid @enderror"
                                          id="setting_{{ $setting->key }}"
                                          name="settings[{{ $setting->key }}]"
                                          rows="4"
                                          placeholder="Nhập {{ strtolower($setting->label) }}">{{ old('settings.'.$setting->key, $setting->value) }}</textarea>

                                @elseif($setting->type === 'url')
                                <input type="url"
                                       class="form-control @error('settings.'.$setting->key) is-invalid @enderror"
                                       id="setting_{{ $setting->key }}"
                                       name="settings[{{ $setting->key }}]"
                                       value="{{ old('settings.'.$setting->key, $setting->value) }}"
                                       placeholder="https://example.com">

                                @elseif($setting->type === 'email')
                                <input type="email"
                                       class="form-control @error('settings.'.$setting->key) is-invalid @enderror"
                                       id="setting_{{ $setting->key }}"
                                       name="settings[{{ $setting->key }}]"
                                       value="{{ old('settings.'.$setting->key, $setting->value) }}"
                                       placeholder="email@example.com">

                                @elseif($setting->type === 'boolean')
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="setting_{{ $setting->key }}"
                                           name="settings[{{ $setting->key }}]"
                                           value="1"
                                           {{ old('settings.'.$setting->key, $setting->value) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="setting_{{ $setting->key }}">
                                        Bật/Tắt
                                    </label>
                                </div>

                                @elseif($setting->type === 'image')
                                <div class="mb-3">
                                    @if($setting->value)
                                    <div class="current-image mb-2">
                                        <img src="{{ $setting->formatted_value }}"
                                             alt="{{ $setting->label }}"
                                             class="img-thumbnail"
                                             style="max-height: 150px;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="remove_{{ $setting->key }}"
                                                   name="remove_images[]"
                                                   value="{{ $setting->key }}">
                                            <label class="form-check-label text-danger" for="remove_{{ $setting->key }}">
                                                Xóa hình ảnh hiện tại
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                    <input type="file"
                                           class="form-control @error('settings.'.$setting->key) is-invalid @enderror"
                                           id="setting_{{ $setting->key }}"
                                           name="settings[{{ $setting->key }}]"
                                           accept="image/*">
                                    <div class="form-text">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</div>
                                </div>

                                @endif

                                @error('settings.'.$setting->key)
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Submit Button for mobile -->
            <div class="d-lg-none mb-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function scrollToGroup(group) {
    const element = document.getElementById('group-' + group);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang lưu...';
    });

    // Preview image uploads
    const imageInputs = document.querySelectorAll('input[type="file"][accept="image/*"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview if doesn't exist
                    let preview = input.parentNode.querySelector('.image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.className = 'image-preview mt-2';
                        input.parentNode.appendChild(preview);
                    }
                    preview.innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px;">
                        <div class="form-text">Xem trước hình ảnh mới</div>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    });
});
</script>
@endpush

@php
function getGroupIcon($group) {
    $icons = [
        'general' => 'home',
        'contact' => 'phone',
        'social' => 'share-alt',
        'business' => 'business-time',
        'order' => 'shopping-cart'
    ];
    return $icons[$group] ?? 'cog';
}

function getGroupName($group) {
    $names = [
        'general' => 'Thông tin chung',
        'contact' => 'Thông tin liên hệ',
        'social' => 'Mạng xã hội',
        'business' => 'Kinh doanh',
        'order' => 'Đơn hàng'
    ];
    return $names[$group] ?? ucfirst($group);
}
@endphp
