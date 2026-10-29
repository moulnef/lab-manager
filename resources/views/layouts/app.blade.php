<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mượn thiết bị phòng Lab') · Lab Manager</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    {{-- Thư viện đặt sẵn trong public/vendor: chạy được cả khi không có Internet --}}
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lab.css') }}" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; background: var(--lab-bg); color: var(--lab-ink); }
        main { flex: 1; }
        .user-nav { background: #0e0f14; border-bottom: 1px solid var(--lab-border); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4); }
        .user-nav .navbar-brand { color: #fff !important; }
        .user-nav .nav-link { color: #94a3b8; font-weight: 500; border-radius: .5rem; padding: .45rem .8rem !important; transition: all .15s ease; }
        .user-nav .nav-link:hover { color: #f87171; background: rgba(220, 38, 38, 0.08); }
        .user-nav .nav-link.active { color: #fff !important; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); box-shadow: 0 2px 10px rgba(220, 38, 38, 0.35); }
        .brand-mark { width: 34px; height: 34px; border-radius: .6rem; background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: #fff;
                      display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 0 14px rgba(239, 68, 68, 0.4); }
        .footer-dark { background: #0c0d12; border-top: 1px solid var(--lab-border); color: #94a3b8; }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg user-nav sticky-top py-2">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
            <span class="brand-mark"><i class="bi bi-cpu"></i></span>
            <span>Lab Manager</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar"
                aria-controls="userNavbar" aria-expanded="false" aria-label="Mở menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="userNavbar">
            <ul class="navbar-nav me-auto gap-lg-1 mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house me-1"></i>Trang chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('devices.*') ? 'active' : '' }}" href="{{ route('devices.index') }}">
                        <i class="bi bi-grid me-1"></i>Thiết bị
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('borrow.create') ? 'active' : '' }}" href="{{ route('borrow.create') }}">
                            <i class="bi bi-box-arrow-in-down me-1"></i>Mượn thiết bị
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('borrow.history') ? 'active' : '' }}" href="{{ route('borrow.history') }}">
                            <i class="bi bi-clock-history me-1"></i>Lịch sử mượn của tôi
                        </a>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-2 mb-2 mb-lg-0">
                @auth
                    @if (auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="btn btn-sm btn-warning fw-semibold" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>Trang quản trị
                            </a>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span class="fw-semibold">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li class="dropdown-header small">{{ auth()->user()->email }}</li>
                            <li><a class="dropdown-item" href="{{ route('borrow.history') }}"><i class="bi bi-clock-history me-2"></i>Lịch sử mượn</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-sm px-3 w-100" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm px-3 w-100" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i>Đăng ký
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    <x-flash-message />
    @yield('content')
</main>

<footer class="footer-dark py-3 mt-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between gap-1 small">
        <span>&copy; {{ date('Y') }} Lab Manager – Hệ thống mượn thiết bị phòng thí nghiệm</span>
        <span>Đồ án Lập trình mã nguồn mở · Laravel {{ app()->version() }}</span>
    </div>
</footer>

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    // Bỏ viền đỏ báo lỗi ngay khi người dùng sửa lại ô nhập
    document.addEventListener('input', (e) => e.target.classList?.remove('is-invalid'));
    document.addEventListener('change', (e) => e.target.classList?.remove('is-invalid'));
    // Tự ẩn thông báo thành công sau 5 giây
    document.querySelectorAll('.alert-success[data-flash]').forEach(el =>
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(el).close(), 5000));
</script>
@stack('scripts')
</body>
</html>
