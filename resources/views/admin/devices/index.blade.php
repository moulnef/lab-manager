@extends('layouts.admin')

@section('title', 'Thiết bị')
@section('page-title', 'Quản lý thiết bị')

@section('page-actions')
    <a href="{{ route('admin.devices.trash') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-trash3"></i><span class="d-none d-md-inline ms-1">Thùng rác ({{ $trashedCount }})</span></a>
    <a href="{{ route('admin.devices.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i><span class="d-none d-sm-inline ms-1">Thêm thiết bị</span></a>
@endsection

@section('content')
    {{-- Lọc nhanh theo trạng thái (đếm bằng GROUP BY) --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        @php $current = $filters['status'] ?? ''; @endphp
        <a href="{{ route('admin.devices.index', \Illuminate\Support\Arr::except($filters, 'status')) }}"
           class="btn btn-sm {{ $current === '' ? 'btn-dark' : 'btn-outline-secondary' }}">
            Tất cả <span class="badge bg-light text-dark ms-1">{{ $statusCounts->sum() }}</span>
        </a>
        @foreach (\App\Models\Device::STATUSES as $value => $label)
            <a href="{{ route('admin.devices.index', array_merge($filters, ['status' => $value])) }}"
               class="btn btn-sm {{ $current === $value ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ $label }} <span class="badge bg-light text-dark ms-1">{{ $statusCounts[$value] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    {{-- Tìm kiếm + lọc; phân trang giữ nguyên tham số nhờ withQueryString() --}}
    <form method="GET" action="{{ route('admin.devices.index') }}" class="card card-body shadow-sm mb-3">
        @if ($current)
            <input type="hidden" name="status" value="{{ $current }}">
        @endif
        <div class="row g-2">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="search" name="keyword" value="{{ $filters['keyword'] ?? '' }}" class="form-control" placeholder="Tên hoặc mã thiết bị...">
                </div>
            </div>
            <div class="col-8 col-md-4">
                <select name="category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4 col-md-2 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Tìm</button>
                @if (array_filter($filters))
                    <a href="{{ route('admin.devices.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-header bg-white small text-muted">
            @if ($devices->total())
                Hiển thị {{ $devices->firstItem() }}–{{ $devices->lastItem() }} / {{ $devices->total() }} thiết bị
            @else
                Không có thiết bị phù hợp
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width:820px">
                <thead>
                    <tr>
                        <th>Thiết bị</th>
                        <th>Danh mục</th>
                        <th>Trạng thái</th>
                        <th>Cập nhật</th>
                        <th class="text-end" style="width:200px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($devices as $device)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <x-device-thumb :device="$device" size="sm" />
                                    <div>
                                        <a href="{{ route('admin.devices.show', $device) }}" class="fw-semibold text-reset text-decoration-none">{{ $device->name }}</a>
                                        <div class="device-code">{{ $device->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="small">{{ $device->category->name }}</td>
                            <td><x-status-badge :model="$device" /></td>
                            <td class="small text-muted">{{ $device->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.devices.show', $device) }}" class="btn btn-outline-secondary" title="Lịch sử mượn"><i class="bi bi-clock-history"></i></a>
                                    <a href="{{ route('admin.devices.edit', $device) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i> Sửa</a>
                                    <form action="{{ route('admin.devices.destroy', $device) }}" method="POST" class="d-inline"
                                          data-confirm="Chuyển &quot;{{ $device->name }}&quot; vào thùng rác?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger rounded-start-0" title="Xóa (có thể khôi phục)"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="bi bi-search"></i>Không tìm thấy thiết bị phù hợp.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($devices->hasPages())
            <div class="card-footer bg-white d-flex justify-content-center">{{ $devices->onEachSide(1)->links() }}</div>
        @endif
    </div>
@endsection
