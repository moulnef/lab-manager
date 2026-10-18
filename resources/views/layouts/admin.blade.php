<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị hệ thống') - Lab Manager Admin</title>

    {{-- Bootstrap 5 qua CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Font Outfit & Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #2563eb;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-hover: #1e293b;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            transition: all 0.3s ease;
            z-index: 1040;
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            margin: 0.2rem 0.75rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        #sidebar .nav-link i {
            font-size: 1.15rem;
            margin-right: 0.75rem;
        }

        #sidebar .nav-link:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
        }

        #sidebar .nav-link.active {
            color: #ffffff;
            background-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        /* Main Content Layout */
        #content-wrapper {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            height: 64px;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Responsive horizontal scrolling for tables */
        .table-responsive {
            border-radius: 0.5rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 991.98px) {
            #sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                bottom: 0;
            }
            #sidebar.show {
                left: 0;
            }
            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1030;
                display: none;
            }
            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex">

    <!-- Mobile Backdrop -->
    <div id="sidebarBackdrop" class="sidebar-backdrop" onclick="toggleSidebar()"></div>

    <!-- Sidebar Điều Hướng -->
    <aside id="sidebar" class="d-flex flex-column flex-shrink-0">
        <div class="px-4 py-3 border-bottom border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center">
                <div class="bg-primary p-2 rounded-3 me-2 d-flex align-items-center justify-content-center text-white">
                    <i class="bi bi-shield-check fs-5"></i>
                </div>
                <div>
                    <span class="fs-5 fw-bold tracking-tight">Lab Admin</span>
                    <span class="badge bg-primary-subtle text-primary ms-1 small">v1.0</span>
                </div>
            </a>
            <button class="btn btn-sm text-secondary d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="p-3">
            <small class="text-uppercase fw-bold text-muted px-3" style="font-size: 0.7rem; letter-spacing: 1px;">Menu Quản Trị</small>
        </div>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Bảng điều khiển
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.devices.index') }}" class="nav-link {{ request()->routeIs('admin.devices.index') || request()->routeIs('admin.devices.edit') ? 'active' : '' }}">
                    <i class="bi bi-laptop"></i> Quản lý thiết bị
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.devices.create') }}" class="nav-link {{ request()->routeIs('admin.devices.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i> Thêm thiết bị mới
                </a>
            </li>

            <li class="px-3 my-3">
                <hr class="border-secondary border-opacity-25 my-0">
            </li>

            <div class="px-3 mb-2">
                <small class="text-uppercase fw-bold text-muted px-3" style="font-size: 0.7rem; letter-spacing: 1px;">Liên Kết Phụ</small>
            </div>

            <li class="nav-item">
                <a href="{{ url('/devices') }}" class="nav-link" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> Giao diện User <span class="badge bg-secondary ms-auto small">Khách</span>
                </a>
            </li>
        </ul>

        <!-- User Info Bottom -->
        <div class="p-3 border-top border-secondary border-opacity-25">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="text-white text-truncate fw-semibold small">{{ Auth::user()->name ?? 'Quản trị viên' }}</div>
                    <div class="text-muted text-truncate" style="font-size: 0.75rem;">Admin / Security</div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger p-1 rounded-2" title="Đăng xuất">
                        <i class="bi bi-box-arrow-right fs-6"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div id="content-wrapper">
        <!-- Header / Top Navbar -->
        <header class="top-navbar px-4 d-flex align-items-center justify-content-between sticky-top">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary btn-sm me-3 d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h5 class="mb-0 fw-bold">@yield('page-title', 'Bảng điều khiển')</h5>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                    <i class="bi bi-dot"></i> Đang trực tuyến
                </span>
                <a href="{{ route('admin.devices.create') }}" class="btn btn-primary btn-sm d-none d-sm-inline-flex align-items-center">
                    <i class="bi bi-plus-lg me-1"></i> Thêm thiết bị
                </a>
                <div class="dropdown ms-2">
                    <button class="btn btn-light btn-sm rounded-circle p-2 border" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li class="dropdown-header">Đăng nhập bởi: <strong>{{ Auth::user()->email ?? 'admin@gmail.com' }}</strong></li>
                        <li><a class="dropdown-item" href="{{ url('/devices') }}"><i class="bi bi-eye me-2"></i>Xem trang Client</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dynamic Main Body Content -->
        <main class="p-4">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-octagon-fill fs-5 me-2"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>
</html>
