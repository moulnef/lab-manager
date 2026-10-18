@extends('layouts.app')

@section('title', 'Đăng nhập hệ thống')

@section('content')
<div class="row justify-content-center my-4">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white text-center py-3 rounded-top-3">
                <h4 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2"></i>Đăng nhập</h4>
                <small class="text-white-50">Hệ thống Quản lý Thiết bị Phòng Lab</small>
            </div>
            <div class="card-body p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Gợi ý tài khoản demo --}}
                <div class="alert alert-info py-2 px-3 small mb-4">
                    <strong><i class="bi bi-info-circle me-1"></i>Tài khoản Admin mẫu:</strong><br>
                    Email: <code>admin@gmail.com</code> | Mật khẩu: <code>12345678</code>
                </div>

                <form action="{{ route('login') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="name@example.com"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Nhập mật khẩu của bạn"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label text-muted" for="remember">Ghi nhớ đăng nhập</label>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
                        </button>
                    </div>

                    <div class="text-center text-muted small">
                        Chưa có tài khoản? <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Đăng ký thành viên mới</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
