<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') · Lab Manager Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- Thư viện đặt sẵn trong public/vendor: chạy được cả khi không có Internet --}}
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lab.css') }}" rel="stylesheet">
    <style>
        :root { --sidebar-w: 256px; --sidebar-bg: #090a0e; }
        body { min-height: 100vh; background: var(--lab-bg); color: var(--lab-ink); }
        #sidebar {
            width: var(--sidebar-w); background: var(--sidebar-bg); color: #cbd5e1;
            position: fixed; inset: 0 auto 0 0; z-index: 1045; display: flex; flex-direction: column;
            transition: transform .25s ease; overflow-y: auto; border-right: 1px solid var(--lab-border);
        }
        #sidebar .brand { color: #fff; text-decoration: none; }
        #sidebar .section { font-size: .7rem; letter-spacing: .08em; text-transform: uppercase; color: #64748b; padding: 1rem 1.5rem .4rem; }
        #sidebar .nav-link {
            color: #cbd5e1; margin: .1rem .75rem; padding: .6rem .9rem; border-radius: .6rem;
            display: flex; align-items: center; gap: .7rem; font-weight: 500; transition: all .15s ease;
        }
        #sidebar .nav-link:hover { background: #161822; color: #f87171; }
        #sidebar .nav-link.active {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(220, 38, 38, 0.4);
        }
        #sidebar .nav-link .badge { margin-left: auto; }
        #content { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; background: var(--lab-bg); }
        .admin-topbar { background: #0e0f14; border-bottom: 1px solid var(--lab-border); height: 64px; }
        .sidebar-backdrop { display: none; }
        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: none; }
            #content { margin-left: 0; }
            .sidebar-backdrop.show { display: block; position: fixed; inset: 0; background: rgba(0, 0, 0, .7); z-index: 1040; }
        }
    </style>
    @stack('styles')
</head>
<body>
@php
    $pendingBadge = \App\Models\Transaction::where('status', 'pending')->count();
@endphp

<div id="sidebarBackdrop" class="sidebar-backdrop" onclick="toggleSidebar()"></div>

<aside id="sidebar" aria-label="Menu quản trị">
    <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom border-secondary border-opacity-25">
        <a href="{{ route('admin.dashboard') }}" class="brand d-flex align-items-center gap-2">
            <span class="bg-danger rounded-3 d-inline-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;box-shadow:0 0 12px rgba(220,38,38,0.4)">
                <i class="bi bi-shield-lock"></i>
            </span>
            <span class="fw-bold fs-5">Lab Admin</span>
        </a>
        <button class="btn btn-sm text-secondary d-lg-none" onclick="toggleSidebar()" aria-label="Đóng menu"><i class="bi bi-x-lg"></i></button>
    </div>

    <div class="section">Tổng quan</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>Tổng quan
    </a>

    <div class="section">Quản lý dữ liệu</div>
    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags"></i>Danh mục
    </a>
    <a href="{{ route('admin.devices.index') }}" class="nav-link {{ request()->routeIs('admin.devices.*') && ! request()->routeIs('admin.devices.trash') ? 'active' : '' }}">
        <i class="bi bi-pc-display"></i>Thiết bị
    </a>
    <a href="{{ route('admin.devices.trash') }}" class="nav-link {{ request()->routeIs('admin.devices.trash') ? 'active' : '' }}">
        <i class="bi bi-trash3"></i>Thùng rác
    </a>

    <div class="section">Nghiệp vụ</div>
    <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-left-right"></i>Phiếu mượn – trả
        @if ($pendingBadge > 0)
            <span class="badge rounded-pill text-bg-warning">{{ $pendingBadge }}</span>
        @endif
    </a>

    <div class="section">Liên kết</div>
    <a href="{{ route('home') }}" class="nav-link"><i class="bi bi-globe2"></i>Xem trang người dùng</a>

    <div class="mt-auto p-3 border-top border-secondary border-opacity-25 d-flex align-items-center gap-2">
        <span class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center text-white" style="width:36px;height:36px;box-shadow:0 0 10px rgba(220,38,38,0.3)">
            <i class="bi bi-person-fill"></i>
        </span>
        <div class="flex-grow-1 overflow-hidden">
            <div class="text-white small fw-semibold text-truncate">{{ auth()->user()->name }}</div>
            <div class="text-secondary text-truncate" style="font-size:.75rem">{{ auth()->user()->email }}</div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button class="btn btn-sm btn-outline-danger" title="Đăng xuất"><i class="bi bi-box-arrow-right"></i></button>
        </form>
    </div>
</aside>

<div id="content">
    <header class="admin-topbar sticky-top d-flex align-items-center justify-content-between px-3 px-md-4">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            <button class="btn btn-outline-secondary btn-sm d-lg-none" onclick="toggleSidebar()" aria-label="Mở menu"><i class="bi bi-list fs-5"></i></button>
            <h1 class="h5 fw-bold mb-0 text-truncate">@yield('page-title', 'Tổng quan')</h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            @yield('page-actions')
        </div>
    </header>

    <main class="p-3 p-md-4 flex-grow-1">
        <x-flash-message />
        @yield('content')
    </main>

    <footer class="px-4 py-3 small text-muted border-top" style="background:#0a0b0f;border-color:var(--lab-border)!important">
        Lab Manager Admin · {{ now()->format('d/m/Y') }}
    </footer>
</div>

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
        document.getElementById('sidebarBackdrop').classList.toggle('show');
    }
    document.querySelectorAll('.alert-success[data-flash]').forEach(el =>
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(el).close(), 5000));

    // Bỏ viền đỏ báo lỗi ngay khi người dùng sửa lại ô nhập
    document.addEventListener('input', (e) => e.target.classList?.remove('is-invalid'));
    document.addEventListener('change', (e) => e.target.classList?.remove('is-invalid'));
    // Hộp xác nhận cho các form có data-confirm (Xóa, Duyệt, Trả...)
    document.addEventListener('submit', (e) => {
        const msg = e.target.dataset.confirm;
        if (msg && !window.confirm(msg)) e.preventDefault();
    });
</script>
@stack('scripts')
</body>
</html>
