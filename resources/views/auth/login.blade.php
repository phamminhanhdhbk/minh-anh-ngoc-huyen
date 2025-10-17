@extends('layouts.app')

@section('title', 'Đăng nhập - Shop VO')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card auth-card">
                <div class="card-header text-center bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required autocomplete="email" autofocus
                                   placeholder="Nhập email của bạn">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Mật khẩu
                            </label>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password"
                                   required autocomplete="current-password"
                                   placeholder="Nhập mật khẩu">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                            </button>
                        </div>

                        <div class="text-center">
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    <i class="fas fa-question-circle me-1"></i>Quên mật khẩu?
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center bg-light">
                    <small class="text-muted">
                        Chưa có tài khoản?
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-decoration-none">
                                Đăng ký ngay
                            </a>
                        @endif
                    </small>
                </div>
            </div>

            <!-- Demo Account Info -->
            <div class="card mt-3">
                <div class="card-body text-center">
                    <h6 class="text-muted">Tài khoản demo:</h6>
                    <p class="mb-1"><strong>Admin:</strong> admin@shopvo.com / admin123</p>
                    <p class="mb-0"><strong>User:</strong> user@example.com / password</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
