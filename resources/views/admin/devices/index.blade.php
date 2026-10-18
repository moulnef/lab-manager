@extends('layouts.admin')

@section('title', 'Quản lý thiết bị')
@section('page-title', 'Danh sách Thiết bị')

@section('content')
<div class="container-fluid p-0">
    <!-- Header bar with Stats Chips and Add Button -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <a href="{{ route('admin.devices.index') }}" class="btn btn-sm {{ empty($statusFilter) ? 'btn-dark' : 'btn-outline-secondary' }}">
                Tất cả <span class="badge {{ empty($statusFilter) ? 'bg-light text-dark' : 'bg-secondary' }} ms-1">{{ $totalCount }}</span>
            </a>
            <a href="{{ route('admin.devices.index', ['status' => 'available']) }}" class="btn btn-sm {{ $statusFilter === 'available' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="bi bi-check-circle me-1"></i>Đang rảnh <span class="badge bg-light text-success ms-1">{{ $statusCounts['available'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.devices.index', ['status' => 'borrowed']) }}" class="btn btn-sm {{ $statusFilter === 'borrowed' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                <i class="bi bi-clock me-1"></i>Đang mượn <span class="badge bg-light text-dark ms-1">{{ $statusCounts['borrowed'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.devices.index', ['status' => 'maintenance']) }}" class="btn btn-sm {{ $statusFilter === 'maintenance' ? 'btn-danger' : 'btn-outline-danger' }}">
                <i class="bi bi-tools me-1"></i>Bảo trì <span class="badge bg-light text-danger ms-1">{{ $statusCounts['maintenance'] ?? 0 }}</span>
            </a>
        </div>

        <div>
            <a href="{{ route('admin.devices.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Thêm thiết bị mới
            </a>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.devices.index') }}" method="GET" class="row g-2 align-items-center">
                @if ($statusFilter)
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif
                <div class="col-12 col-md-9 col-lg-10">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="search"
                               name="search"
                               value="{{ $search }}"
                               class="form-control border-start-0 ps-0"
                               placeholder="Tìm kiếm thiết bị theo tên hoặc chủng loại...">
                    </div>
                </div>
                <div class="col-12 col-md-3 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Tìm
                    </button>
                    @if ($search || $statusFilter)
                        <a href="{{ route('admin.devices.index') }}" class="btn btn-outline-secondary" title="Đặt lại bộ lọc">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Main Data Table Container with Responsive Horizontal Scrolling -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-table me-2 text-primary"></i>Dữ liệu thiết bị phòng Lab
            </h6>
            <span class="text-muted small">Hiển thị {{ $devices->count() }} / {{ $devices->total() }} thiết bị</span>
        </div>

        <div class="card-body p-0">
            {{-- Responsive table container --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 750px;">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 80px;">Mã số</th>
                            <th>Tên thiết bị</th>
                            <th>Chủng loại</th>
                            <th>Trạng thái</th>
                            <th>Ngày khởi tạo</th>
                            <th class="text-end pe-4" style="width: 160px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($devices as $device)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#{{ $device->id }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $device->name }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <i class="bi bi-tag me-1 text-primary"></i>{{ $device->type }}
                                    </span>
                                </td>
                                <td>
                                    @if ($device->status === 'available')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-check-circle-fill me-1"></i>Đang rảnh
                                        </span>
                                    @elseif ($device->status === 'borrowed')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                            <i class="bi bi-clock-fill me-1"></i>Đang mượn
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                            <i class="bi bi-tools me-1"></i>Bảo trì
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $device->created_at ? $device->created_at->format('d/m/Y H:i') : '---' }}
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.devices.edit', $device) }}"
                                           class="btn btn-outline-primary"
                                           title="Chỉnh sửa thiết bị">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                        <button type="button"
                                                class="btn btn-outline-danger"
                                                onclick="confirmDelete({{ $device->id }}, '{{ addslashes($device->name) }}')"
                                                title="Xóa thiết bị">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    {{-- Form xóa ẩn chuẩn RESTful DELETE --}}
                                    <form id="delete-form-{{ $device->id }}"
                                          action="{{ route('admin.devices.destroy', $device) }}"
                                          method="POST"
                                          class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                    Không tìm thấy thiết bị nào phù hợp với điều kiện tìm kiếm.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($devices->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
                {{ $devices->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        if (confirm('Bạn có chắc chắn muốn xóa thiết bị "' + name + '" không?\nHành động này không thể hoàn tác!')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
