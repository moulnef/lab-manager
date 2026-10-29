@extends('layouts.admin')

@section('title', 'Thùng rác')
@section('page-title', 'Thùng rác thiết bị')

@section('page-actions')
    <a href="{{ route('admin.devices.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i><span class="d-none d-sm-inline ms-1">Danh sách thiết bị</span></a>
@endsection

@section('content')
    <div class="alert alert-info small">
        <i class="bi bi-info-circle me-1"></i>
        Thiết bị bị xóa được <strong>xóa mềm</strong> (SoftDeletes): dữ liệu và lịch sử mượn vẫn được giữ lại, bạn có thể khôi phục bất cứ lúc nào.
    </div>

    <form method="GET" class="card card-body shadow-sm mb-3">
        <div class="row g-2">
            <div class="col-12 col-md-8 col-lg-6">
                <input type="search" name="keyword" value="{{ $keyword }}" class="form-control" placeholder="Tìm theo tên hoặc mã...">
            </div>
            <div class="col-12 col-md-4 col-lg-2"><button class="btn btn-primary w-100">Tìm</button></div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="min-width:640px">
                <thead><tr><th>Thiết bị</th><th>Danh mục</th><th>Ngày xóa</th><th class="text-end">Thao tác</th></tr></thead>
                <tbody>
                    @forelse ($devices as $device)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $device->name }}</div>
                                <div class="device-code">{{ $device->code }}</div>
                            </td>
                            <td class="small">{{ $device->category->name }}</td>
                            <td class="small text-muted">{{ $device->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <form action="{{ route('admin.devices.restore', $device->id) }}" method="POST"
                                      data-confirm="Khôi phục &quot;{{ $device->name }}&quot;?">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-success"><i class="bi bi-arrow-counterclockwise me-1"></i>Khôi phục</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state"><i class="bi bi-trash3"></i>Thùng rác đang trống.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($devices->hasPages())
            <div class="card-footer bg-white d-flex justify-content-center">{{ $devices->links() }}</div>
        @endif
    </div>
@endsection
