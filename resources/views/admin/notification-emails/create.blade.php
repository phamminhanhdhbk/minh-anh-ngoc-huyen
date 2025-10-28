@extends('admin.layouts.app')

@section('title', 'Thêm Email Nhận Thông Báo - Admin Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>Thêm Email Nhận Thông Báo
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
                <form action="{{ route('admin.notification-emails.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="example@gmail.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên người nhận</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="Nguyễn Văn A">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Loại thông báo <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="order" {{ old('type') == 'order' ? 'selected' : '' }}>Đơn hàng</option>
                            <option value="contact" {{ old('type') == 'contact' ? 'selected' : '' }}>Liên hệ</option>
                            <option value="newsletter" {{ old('type') == 'newsletter' ? 'selected' : '' }}>Newsletter</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <strong>Đơn hàng:</strong> Nhận thông báo khi có đơn hàng mới<br>
                            <strong>Liên hệ:</strong> Nhận thông báo khi có liên hệ từ khách hàng<br>
                            <strong>Newsletter:</strong> Nhận bản tin định kỳ
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">Ghi chú</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" 
                                  id="note" name="note" rows="3" 
                                  placeholder="Ghi chú về email này...">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Kích hoạt (Chỉ email kích hoạt mới nhận thông báo)
                        </label>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.notification-emails.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Thêm Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Hướng Dẫn</h5>
            </div>
            <div class="card-body">
                <h6>Email sẽ được sử dụng để:</h6>
                <ul>
                    <li>Nhận thông báo đơn hàng mới</li>
                    <li>Nhận liên hệ từ khách hàng</li>
                    <li>Nhận bản tin định kỳ</li>
                </ul>
                <hr>
                <p class="mb-0">
                    <i class="fas fa-lightbulb text-warning me-2"></i>
                    <strong>Lưu ý:</strong> Một email có thể nhận nhiều loại thông báo bằng cách thêm cùng email với loại khác nhau.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
