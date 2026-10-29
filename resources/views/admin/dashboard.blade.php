@extends('layouts.admin')

@section('title', 'Tổng quan')
@section('page-title', 'Tổng quan')

@section('page-actions')
    <a href="{{ route('admin.devices.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i><span class="d-none d-sm-inline ms-1">Thêm thiết bị</span></a>
@endsection

@section('content')
    {{-- Thẻ số liệu (COUNT + GROUP BY) --}}
    @php
        $cards = [
            ['Tổng thiết bị', $stats['devices'], 'bi-pc-display', 'primary', route('admin.devices.index')],
            ['Sẵn sàng', $stats['available'], 'bi-check-circle', 'success', route('admin.devices.index', ['status' => 'available'])],
            ['Đang mượn', $stats['borrowed'], 'bi-box-arrow-up-right', 'warning', route('admin.devices.index', ['status' => 'borrowed'])],
            ['Bảo trì', $stats['maintenance'], 'bi-tools', 'danger', route('admin.devices.index', ['status' => 'maintenance'])],
            ['Phiếu chờ duyệt', $stats['pending'], 'bi-hourglass-split', 'info', route('admin.transactions.index', ['status' => 'pending'])],
            ['Quá hạn trả', $stats['overdue'], 'bi-alarm', 'danger', route('admin.transactions.index', ['overdue' => 1])],
        ];
    @endphp
    <div class="row g-3 mb-4">
        @foreach ($cards as [$label, $value, $icon, $color, $link])
            <div class="col-6 col-md-4 col-xl-2">
                <a href="{{ $link }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="text-muted small fw-semibold">{{ $label }}</span>
                                <i class="bi {{ $icon }} text-{{ $color }} fs-5"></i>
                            </div>
                            <div class="fs-3 fw-bold text-dark mt-1">{{ $value }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Biểu đồ vẽ bằng SVG/HTML từ dữ liệu GROUP BY – không cần thư viện JS --}}
    @php
        $donut = [
            ['Sẵn sàng', $stats['available'], '#16a34a'],
            ['Đang mượn', $stats['borrowed'], '#f59e0b'],
            ['Bảo trì', $stats['maintenance'], '#dc2626'],
        ];
        $donutTotal = max(1, array_sum(array_column($donut, 1)));
        $offset = 0;
        $maxCat = max(1, $deviceByCategory->max('total'));
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-5">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white fw-semibold">Thiết bị theo trạng thái</div>
                <div class="card-body d-flex flex-column flex-sm-row align-items-center justify-content-center gap-4">
                    <svg viewBox="0 0 42 42" width="190" height="190" role="img" aria-label="Biểu đồ tròn trạng thái thiết bị">
                        <circle cx="21" cy="21" r="15.915" fill="none" stroke="#eef2f7" stroke-width="6"></circle>
                        @foreach ($donut as [$label, $value, $color])
                            @php $pct = $value / $donutTotal * 100; @endphp
                            @if ($pct > 0)
                                <circle cx="21" cy="21" r="15.915" fill="none" stroke="{{ $color }}" stroke-width="6"
                                        stroke-dasharray="{{ round($pct, 3) }} {{ round(100 - $pct, 3) }}"
                                        stroke-dashoffset="{{ round(25 - $offset, 3) }}"><title>{{ $label }}: {{ $value }}</title></circle>
                            @endif
                            @php $offset += $pct; @endphp
                        @endforeach
                        <text x="21" y="21" text-anchor="middle" font-size="7" font-weight="700" fill="#f8fafc">{{ $stats['devices'] }}</text>
                        <text x="21" y="27" text-anchor="middle" font-size="3" fill="#94a3b8">thiết bị</text>
                    </svg>
                    <ul class="list-unstyled mb-0 small">
                        @foreach ($donut as [$label, $value, $color])
                            <li class="d-flex align-items-center gap-2 mb-2">
                                <span class="rounded-circle d-inline-block" style="width:12px;height:12px;background:{{ $color }}"></span>
                                <span class="me-2">{{ $label }}</span>
                                <strong class="ms-auto">{{ $value }}</strong>
                                <span class="text-muted">({{ round($value / $donutTotal * 100) }}%)</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white fw-semibold">Thiết bị theo danh mục</div>
                <div class="card-body">
                    @foreach ($deviceByCategory as $row)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ $row->name }}</span><strong>{{ $row->total }}</strong>
                            </div>
                            <div class="progress" style="height:10px" role="progressbar" aria-label="{{ $row->name }}"
                                 aria-valuenow="{{ $row->total }}" aria-valuemin="0" aria-valuemax="{{ $maxCat }}">
                                <div class="progress-bar" style="width: {{ $row->total / $maxCat * 100 }}%; background: var(--lab-primary)"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Phiếu mượn gần đây</span>
                    <a href="{{ route('admin.transactions.index') }}" class="small">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width:640px">
                        <thead><tr><th>#</th><th>Người mượn</th><th>Thiết bị</th><th>Thời gian</th><th>Trạng thái</th></tr></thead>
                        <tbody>
                            @forelse ($recentTransactions as $t)
                                <tr>
                                    <td class="text-muted">{{ $t->id }}</td>
                                    <td>{{ $t->user->name }}</td>
                                    <td>{{ $t->device->name }}</td>
                                    <td class="small text-muted">{{ $t->borrow_date->format('d/m') }} → {{ $t->return_date->format('d/m/Y') }}</td>
                                    <td><x-status-badge :model="$t" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">Chưa có phiếu mượn.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Top thiết bị được mượn nhiều</div>
                <ul class="list-group list-group-flush">
                    @forelse ($topDevices as $device)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="text-truncate me-2">
                                <div class="fw-semibold small text-truncate">{{ $device->name }}</div>
                                <div class="device-code">{{ $device->code }}</div>
                            </div>
                            <span class="badge rounded-pill text-bg-primary">{{ $device->borrow_count }} lượt</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">Chưa có dữ liệu.</li>
                    @endforelse
                </ul>
                <div class="card-footer bg-white small text-muted">
                    <i class="bi bi-people me-1"></i>{{ $stats['users'] }} người dùng đã đăng ký
                </div>
            </div>
        </div>
    </div>
@endsection
