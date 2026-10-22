<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản lý thiết bị Phòng Lab')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-sm navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-semibold" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" width="32" height="32">
                Phòng Lab
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-label="Mở menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('user.devices*') ? 'active' : '' }}" href="{{ route('user.devices') }}">Thiết bị</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-3 flex-grow-1">
        <x-flash />
        @yield('content')
    </main>

    <footer class="bg-dark text-light py-4 mt-4">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-8">
                    <h6 class="mb-2">Hệ thống quản lý thiết bị &amp; tài sản Phòng Lab / Văn phòng</h6>
                    <p class="small text-secondary mb-0">
                        Giúp tra cứu thiết bị, gửi yêu cầu mượn trực tuyến và theo dõi tình trạng
                        thiết bị (đang rảnh, đang mượn, bảo trì) nhanh chóng, minh bạch.
                    </p>
                </div>
                <div class="col-md-4 small text-secondary">
                    <div>Mượn thiết bị: chọn thiết bị &rarr; chọn ngày &rarr; chờ duyệt</div>
                    <div>Công nghệ: Laravel, MySQL, Bootstrap</div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
