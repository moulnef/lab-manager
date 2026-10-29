@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="row justify-content-center my-md-4">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="category-icon mb-2"><i class="bi bi-shield-lock"></i></span>
                    <h1 class="h4 fw-bold mb-1">Đăng nhập</h1>
                    <p class="text-muted small mb-0">Hệ thống mượn thiết bị phòng Lab</p>
                </div>

                <form action="{{ route('login') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email"
                               class="form-control @error('email') is-invalid @enderror" placeholder="ban@vidu.com" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" id="password" name="password" autocomplete="current-password"
                               class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small" for="remember">Ghi nhớ đăng nhập</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
                    </button>
                </form>

                <p class="text-center small text-muted mt-4 mb-0">
                    Chưa có tài khoản? <a href="{{ route('register') }}" class="fw-semibold">Đăng ký ngay</a>
                </p>
            </div>
            <div class="card-footer bg-light small text-muted">
                <i class="bi bi-info-circle me-1"></i>Tài khoản demo –
                Admin: <code>admin@gmail.com</code> · User: <code>user@gmail.com</code> · Mật khẩu: <code>12345678</code>
            </div>
        </div>
    </div>
</div>
@endsection
