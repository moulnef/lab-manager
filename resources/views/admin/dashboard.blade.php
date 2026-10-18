@extends('layouts.admin')

@section('title', 'Bảng điều khiển Quản trị')
@section('page-title', 'Bảng điều khiển Thống kê')

@section('content')
<div class="container-fluid p-0">
    <!-- Row 1: Thẻ thống kê KPI (Tổng hợp số liệu từ GROUP BY) -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 me-3">
                        <i class="bi bi-cpu fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Tổng số thiết bị</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalDevices }}</h3>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-2 px-3 small text-muted">
                    <i class="bi bi-check2-circle text-success me-1"></i> Tất cả thiết bị trong kho
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-3 me-3">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Thiết bị sẵn sàng</div>
                        <h3 class="fw-bold mb-0 text-success">{{ $availableCount }}</h3>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-2 px-3 small text-muted">
                    <span class="badge bg-success-subtle text-success me-1">
                        {{ $totalDevices > 0 ? round(($availableCount / $totalDevices) * 100) : 0 }}%
                    </span> Sẵn sàng cho mượn
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3 me-3">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Đang được mượn</div>
                        <h3 class="fw-bold mb-0 text-warning">{{ $borrowedCount }}</h3>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-2 px-3 small text-muted">
                    <span class="badge bg-warning-subtle text-warning me-1">
                        {{ $totalDevices > 0 ? round(($borrowedCount / $totalDevices) * 100) : 0 }}%
                    </span> Sinh viên/GV đang sử dụng
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3 me-3">
                        <i class="bi bi-tools fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Đang bảo trì</div>
                        <h3 class="fw-bold mb-0 text-danger">{{ $maintenanceCount }}</h3>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-2 px-3 small text-muted">
                    <span class="badge bg-danger-subtle text-danger me-1">Cần xử lý</span> Tạm ngưng mượn
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Biểu đồ thống kê sử dụng Chart.js & Dữ liệu GROUP BY MySQL -->
    <div class="row g-3 mb-4">
        <!-- Biểu đồ tròn: Trạng thái thiết bị -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-pie-chart me-2 text-primary"></i>Tỷ lệ trạng thái (GROUP BY status)
                    </h6>
                    <span class="badge bg-primary-subtle text-primary small">MySQL GROUP BY</span>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                    <div style="position: relative; width: 100%; max-width: 280px; height: 260px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mt-3 small">
                        <span><i class="bi bi-circle-fill text-success me-1"></i> Rảnh: {{ $availableCount }}</span>
                        <span><i class="bi bi-circle-fill text-warning me-1"></i> Đang mượn: {{ $borrowedCount }}</span>
                        <span><i class="bi bi-circle-fill text-danger me-1"></i> Bảo trì: {{ $maintenanceCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ cột: Phân bố theo chủng loại thiết bị -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-bar-chart-steps me-2 text-success"></i>Số lượng theo loại thiết bị (GROUP BY type)
                    </h6>
                    <span class="badge bg-success-subtle text-success small">MySQL GROUP BY</span>
                </div>
                <div class="card-body p-4">
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="typeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Bảng dữ liệu thiết bị mới và giao dịch gần đây -->
    <div class="row g-3">
        <!-- Bảng thiết bị vừa thêm mới -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-laptop me-2 text-primary"></i>Thiết bị mới cập nhật
                    </h6>
                    <a href="{{ route('admin.devices.index') }}" class="btn btn-sm btn-outline-primary">
                        Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-3">ID</th>
                                    <th>Tên thiết bị</th>
                                    <th>Loại</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end pe-3">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentDevices as $device)
                                    <tr>
                                        <td class="ps-3 fw-bold text-muted">#{{ $device->id }}</td>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $device->name }}</span>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $device->type }}</span></td>
                                        <td>
                                            @if ($device->status === 'available')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                    <i class="bi bi-check-circle me-1"></i>Đang rảnh
                                                </span>
                                            @elseif ($device->status === 'borrowed')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                                    <i class="bi bi-clock me-1"></i>Đang mượn
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                    <i class="bi bi-tools me-1"></i>Bảo trì
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('admin.devices.edit', $device) }}" class="btn btn-sm btn-light border text-primary" title="Sửa">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Chưa có thiết bị nào trong CSDL.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng giao dịch gần đây -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-arrow-repeat me-2 text-warning"></i>Giao dịch mượn máy
                    </h6>
                    <span class="badge bg-light text-muted border">{{ $totalTransactions }} giao dịch</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-3">Người mượn</th>
                                    <th>Thiết bị</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentTransactions as $tx)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-semibold small">{{ $tx->user->name ?? 'Người dùng #' . $tx->user_id }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $tx->borrow_date ?? $tx->created_at->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td class="small">{{ $tx->device->name ?? 'Thiết bị #' . $tx->device_id }}</td>
                                        <td>
                                            @if ($tx->status === 'active')
                                                <span class="badge bg-primary-subtle text-primary">Đang mượn</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">Đã trả</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted small">Chưa có giao dịch mượn máy nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Thư viện Chart.js qua CDN để vẽ biểu đồ thống kê --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Dữ liệu biểu đồ trạng thái từ GROUP BY
        const available = {{ $availableCount }};
        const borrowed = {{ $borrowedCount }};
        const maintenance = {{ $maintenanceCount }};

        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Đang rảnh', 'Đang mượn', 'Bảo trì'],
                datasets: [{
                    data: [available, borrowed, maintenance],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '68%'
            }
        });

        // 2. Dữ liệu biểu đồ loại thiết bị từ GROUP BY
        const typeLabels = {!! json_encode($typeStats->pluck('type')) !!};
        const typeData = {!! json_encode($typeStats->pluck('total')) !!};

        const ctxType = document.getElementById('typeChart').getContext('2d');
        new Chart(ctxType, {
            type: 'bar',
            data: {
                labels: typeLabels,
                datasets: [{
                    label: 'Số lượng thiết bị',
                    data: typeData,
                    backgroundColor: '#3b82f6',
                    borderRadius: 6,
                    maxBarThickness: 38
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
