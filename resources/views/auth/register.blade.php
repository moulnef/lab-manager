@extends('layouts.app')

@section('title', 'Đăng ký tài khoản')

@section('content')
<div class="row justify-content-center my-md-4">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="category-icon mb-2"><i class="bi bi-person-plus"></i></span>
                    <h1 class="h4 fw-bold mb-1">Tạo tài khoản</h1>
                    <p class="text-muted small mb-0">Dành cho sinh viên và giảng viên muốn mượn thiết bị</p>
                </div>

                <form action="{{ route('register') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" autocomplete="name"
                               class="form-control @error('name') is-invalid @enderror" placeholder="Nguyễn Văn A" required autofocus>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email"
                               class="form-control @error('email') is-invalid @enderror" placeholder="ban@vidu.com" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" id="password" name="password" autocomplete="new-password"
                               class="form-control @error('password') is-invalid @enderror" placeholder="Tối thiểu 8 ký tự" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                               class="form-control" placeholder="Nhập lại mật khẩu" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-person-check me-1"></i>Đăng ký
                    </button>
                </form>

                <p class="text-center small text-muted mt-4 mb-0">
                    Đã có tài khoản? <a href="{{ route('login') }}" class="fw-semibold">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
