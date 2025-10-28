@extends('admin.layouts.app')

@section('title', 'Sửa Email Nhận Thông Báo - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>Sửa Email Nhận Thông Báo
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.notification-emails.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.notification-emails.update', $notificationEmail) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $notificationEmail->email) }}" 
                               placeholder="example@gmail.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên người nhận</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $notificationEmail->name) }}" 
                               placeholder="Nguyễn Văn A">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Loại thông báo <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="order" {{ old('type', $notificationEmail->type) == 'order' ? 'selected' : '' }}>Đơn hàng</option>
                            <option value="contact" {{ old('type', $notificationEmail->type) == 'contact' ? 'selected' : '' }}>Liên hệ</option>
                            <option value="newsletter" {{ old('type', $notificationEmail->type) == 'newsletter' ? 'selected' : '' }}>Newsletter</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">Ghi chú</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" 
                                  id="note" name="note" rows="3" 
                                  placeholder="Ghi chú về email này...">{{ old('note', $notificationEmail->note) }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                               {{ old('is_active', $notificationEmail->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Kích hoạt (Chỉ email kích hoạt mới nhận thông báo)
                        </label>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.notification-emails.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập Nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Thông Tin</h5>
            </div>
            <div class="card-body">
                <p><strong>Ngày tạo:</strong><br>{{ $notificationEmail->created_at->format('d/m/Y H:i:s') }}</p>
                <p><strong>Cập nhật:</strong><br>{{ $notificationEmail->updated_at->format('d/m/Y H:i:s') }}</p>
                <hr>
                <p class="mb-0">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    Thay đổi sẽ có hiệu lực ngay lập tức.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
