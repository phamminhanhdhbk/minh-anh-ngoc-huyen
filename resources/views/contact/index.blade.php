@extends('layouts.app')

@section('title', 'Liên hệ - VietNhat365')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-dark mb-3">Chúng tôi rất muốn nghe từ bạn</h1>
                <p class="lead text-muted">
                    Chúng tôi sẽ tìm hiểu về bạn để hiểu rõ mục tiêu bán hàng của bạn, giải thích quy trình bán hàng để bạn biết điều gì đang chờ đợi.
                </p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Contact Form -->
            <div class="contact-form-wrapper">
                <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                    @csrf
                    <div class="row g-4">
                        <!-- Name Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label fw-semibold text-dark">Họ và tên:</label>
                                <input type="text" 
                                       class="form-control modern-input @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Nhập họ và tên của bạn"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label fw-semibold text-dark">Email:</label>
                                <input type="email" 
                                       class="form-control modern-input @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="Nhập địa chỉ email"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label fw-semibold text-dark">Số điện thoại:</label>
                                <input type="tel" 
                                       class="form-control modern-input @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}" 
                                       placeholder="Nhập số điện thoại">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Interest Field (replacing subject) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject" class="form-label fw-semibold text-dark">Bạn quan tâm đến điều gì?</label>
                                <select class="form-select modern-input @error('subject') is-invalid @enderror" 
                                        id="subject" 
                                        name="subject" 
                                        required>
                                    <option value="">Chọn</option>
                                    <option value="Mua sản phẩm" {{ old('subject') == 'Mua sản phẩm' ? 'selected' : '' }}>Mua sản phẩm</option>
                                    <option value="Hỗ trợ kỹ thuật" {{ old('subject') == 'Hỗ trợ kỹ thuật' ? 'selected' : '' }}>Hỗ trợ kỹ thuật</option>
                                    <option value="Hợp tác kinh doanh" {{ old('subject') == 'Hợp tác kinh doanh' ? 'selected' : '' }}>Hợp tác kinh doanh</option>
                                    <option value="Báo giá sản phẩm" {{ old('subject') == 'Báo giá sản phẩm' ? 'selected' : '' }}>Báo giá sản phẩm</option>
                                    <option value="Khiếu nại/Góp ý" {{ old('subject') == 'Khiếu nại/Góp ý' ? 'selected' : '' }}>Khiếu nại/Góp ý</option>
                                    <option value="Khác" {{ old('subject') == 'Khác' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Message Field -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="message" class="form-label fw-semibold text-dark">Tin nhắn của bạn:</label>
                                <textarea class="form-control modern-input @error('message') is-invalid @enderror" 
                                          id="message" 
                                          name="message" 
                                          rows="6" 
                                          placeholder="Nhập nội dung tin nhắn..."
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12">
                            <div class="text-center">
                                <button type="submit" class="btn btn-contact-submit">
                                    Liên hệ với chuyên gia
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Container và layout */
.contact-form-wrapper {
    background: #ffffff;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    margin: 2rem 0;
}

/* Form styling */
.contact-form .form-group {
    margin-bottom: 1.5rem;
}

.contact-form .form-label {
    font-size: 0.95rem;
    margin-bottom: 0.8rem;
    color: #2c3e50;
    font-weight: 600;
}

/* Input styling */
.modern-input {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}

.modern-input:focus {
    border-color: #007bff;
    background-color: #ffffff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.1);
    outline: 0;
}

.modern-input::placeholder {
    color: #adb5bd;
    font-weight: 400;
}

/* Select styling */
.form-select.modern-input {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px 12px;
}

/* Submit button */
.btn-contact-submit {
    background: linear-gradient(135deg, #ff8c42 0%, #ff6b1a 100%);
    border: none;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    padding: 1rem 3rem;
    border-radius: 50px;
    box-shadow: 0 8px 25px rgba(255, 140, 66, 0.3);
    transition: all 0.3s ease;
    text-transform: none;
}

.btn-contact-submit:hover {
    background: linear-gradient(135deg, #ff6b1a 0%, #e85a15 100%);
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(255, 140, 66, 0.4);
    color: white;
}

.btn-contact-submit:active {
    transform: translateY(0);
}

/* Header styling */
.display-5 {
    font-size: 2.5rem;
    line-height: 1.2;
}

.lead {
    font-size: 1.1rem;
    line-height: 1.6;
    max-width: 800px;
    margin: 0 auto;
}

/* Error styling */
.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Success alert */
.alert-success {
    border: none;
    border-radius: 12px;
    background-color: #d1edff;
    border-left: 4px solid #28a745;
    color: #155724;
}

/* Responsive */
@media (max-width: 768px) {
    .contact-form-wrapper {
        padding: 2rem 1.5rem;
        margin: 1rem 0;
    }
    
    .display-5 {
        font-size: 2rem;
    }
    
    .lead {
        font-size: 1rem;
    }
    
    .btn-contact-submit {
        padding: 0.9rem 2.5rem;
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .contact-form-wrapper {
        padding: 1.5rem 1rem;
    }
    
    .modern-input {
        padding: 0.9rem 1rem;
    }
}
</style>
@endsection