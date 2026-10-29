@extends('layouts.admin')

@section('title', $device->name)
@section('page-title', 'Lịch sử mượn thiết bị')

@section('page-actions')
    <a href="{{ route('admin.devices.edit', $device) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i><span class="d-none d-sm-inline ms-1">Sửa</span></a>
@endsection

@section('content')
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex flex-column flex-md-row gap-3 align-items-md-center">
            <x-device-thumb :device="$device" size="sm" style="width:88px;height:88px" />
            <div class="flex-grow-1">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="device-code">{{ $device->code }}</span>
                    <x-status-badge :model="$device" />
                </div>
                <h2 class="h5 fw-bold mb-1">{{ $device->name }}</h2>
                <div class="small text-muted">{{ $device->category->name }} · Tổng {{ $transactions->total() }} phiếu mượn</div>
            </div>
            <a href="{{ route('devices.show', $device) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-box-arrow-up-right me-1"></i>Xem trang công khai
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="min-width:720px">
                <thead><tr><th>Phiếu</th><th>Người mượn</th><th>Thời gian mượn</th><th>Duyệt / Trả</th><th>Trạng thái</th></tr></thead>
                <tbody>
                    @forelse ($transactions as $t)
                        <tr>
                            <td class="text-muted">#{{ $t->id }}</td>
                            <td><div class="fw-semibold">{{ $t->user->name }}</div><div class="small text-muted">{{ $t->user->email }}</div></td>
                            <td class="small">{{ $t->borrow_date->format('d/m/Y') }} → {{ $t->return_date->format('d/m/Y') }}</td>
                            <td class="small text-muted">
                                {{ $t->approved_at?->format('d/m/Y H:i') ?? '—' }}<br>
                                {{ $t->returned_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td><x-status-badge :model="$t" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="bi bi-journal"></i>Thiết bị chưa từng được mượn.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($transactions->hasPages())
            <div class="card-footer bg-white d-flex justify-content-center">{{ $transactions->links() }}</div>
        @endif
    </div>
@endsection
