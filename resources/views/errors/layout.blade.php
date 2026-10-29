<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') · Lab Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    {{-- Thư viện đặt sẵn trong public/vendor: chạy được cả khi không có Internet --}}
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lab.css') }}" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3">
    <div class="card shadow-sm text-center p-4 p-md-5" style="max-width: 480px">
        <span class="category-icon mx-auto mb-3" style="width:64px;height:64px;font-size:2rem"><i class="bi @yield('icon')"></i></span>
        <div class="text-muted fw-semibold">Lỗi @yield('code')</div>
        <h1 class="h4 fw-bold my-2">@yield('heading')</h1>
        <p class="text-muted">@yield('message')</p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="btn btn-outline-secondary">Quay lại</a>
            <a href="{{ url('/') }}" class="btn btn-primary">Về trang chủ</a>
        </div>
    </div>
</body>
</html>
