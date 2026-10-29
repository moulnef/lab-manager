@extends('layouts.admin')

@section('title', 'Phiếu mượn – trả')
@section('page-title', 'Phiếu mượn – trả')

@section('content')
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.transactions.index', array_filter(['keyword' => $keyword])) }}"
           class="btn btn-sm {{ ! $status && ! $overdue ? 'btn-dark' : 'btn-outline-secondary' }}">
            Tất cả <span class="badge bg-light text-dark ms-1">{{ $counts->sum() }}</span>
        </a>
        @foreach (\App\Models\Transaction::STATUSES as $value => $label)
            <a href="{{ route('admin.transactions.index', array_filter(['status' => $value, 'keyword' => $keyword])) }}"
               class="btn btn-sm {{ $status === $value ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ $label }} <span class="badge bg-light text-dark ms-1">{{ $counts[$value] ?? 0 }}</span>
            </a>
        @endforeach
        <a href="{{ route('admin.transactions.index', ['overdue' => 1]) }}"
           class="btn btn-sm {{ $overdue ? 'btn-danger' : 'btn-outline-danger' }}"><i class="bi bi-alarm me-1"></i>Quá hạn</a>
    </div>

    <form method="GET" class="card card-body shadow-sm mb-3">
        @if ($status)
            <input type="hidden" name="status" value="{{ $status }}">
        @endif
        <div class="row g-2">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="search" name="keyword" value="{{ $keyword }}" class="form-control" placeholder="Tên/email người mượn, tên/mã thiết bị...">
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-2"><button class="btn btn-primary w-100">Tìm</button></div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width:960px">
                <thead>
                    <tr>
                        <th>Phiếu</th>
                        <th>Người mượn</th>
                        <th>Thiết bị</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th class="text-end" style="width:230px">Xử lý</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $t)
                        <tr class="{{ $t->isOverdue() ? 'table-danger' : '' }}">
                            <td>
                                <div class="fw-semibold">#{{ $t->id }}</div>
                                <div class="small text-muted">{{ $t->created_at->format('d/m H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $t->user->name }}</div>
                                <div class="small text-muted">{{ $t->user->email }}</div>
                            </td>
                            <td style="max-width:260px">
                                <div class="fw-semibold text-truncate">{{ $t->device->name }}</div>
                                <div class="device-code">{{ $t->device->code }} · <x-status-badge :model="$t->device" class="small" /></div>
                                @if ($t->purpose)
                                    <div class="small text-muted text-truncate" title="{{ $t->purpose }}">{{ $t->purpose }}</div>
                                @endif
                            </td>
                            <td class="small">
                                {{ $t->borrow_date->format('d/m/Y') }} → {{ $t->return_date->format('d/m/Y') }}
                                @if ($t->isOverdue())
                                    <div class="text-danger fw-semibold">Quá hạn {{ (int) $t->return_date->diffInDays(today()) }} ngày</div>
                                @endif
                                @if ($t->returned_at)
                                    <div class="text-success">Trả: {{ $t->returned_at->format('d/m/Y H:i') }}</div>
                                @endif
                            </td>
                            <td>
                                <x-status-badge :model="$t" />
                                @if ($t->admin_note)
                                    <div class="small text-muted mt-1">{{ $t->admin_note }}</div>
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($t->status === 'pending')
                                    <div class="d-inline-flex gap-1">
                                        <form action="{{ route('admin.transactions.approve', $t) }}" method="POST"
                                              data-confirm="Duyệt phiếu #{{ $t->id }}? Thiết bị sẽ chuyển sang Đang mượn.">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-success"><i class="bi bi-check2 me-1"></i>Duyệt</button>
                                        </form>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal"
                                                data-action="{{ route('admin.transactions.reject', $t) }}" data-id="{{ $t->id }}">
                                            <i class="bi bi-x-lg me-1"></i>Từ chối
                                        </button>
                                    </div>
                                @elseif ($t->status === 'approved')
                                    <form action="{{ route('admin.transactions.return', $t) }}" method="POST"
                                          data-confirm="Xác nhận đã nhận lại thiết bị của phiếu #{{ $t->id }}?">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-primary"><i class="bi bi-box-arrow-in-left me-1"></i>Xác nhận trả</button>
                                    </form>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-inbox"></i>Không có phiếu mượn phù hợp.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($transactions->hasPages())
            <div class="card-footer bg-white d-flex justify-content-center">{{ $transactions->onEachSide(1)->links() }}</div>
        @endif
    </div>

    {{-- Hộp thoại nhập lý do từ chối --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content" id="rejectForm">
                @csrf @method('PATCH')
                <div class="modal-header">
                    <h2 class="modal-title h6 fw-bold" id="rejectModalLabel">Từ chối phiếu <span></span></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <label for="admin_note" class="form-label">Lý do từ chối</label>
                    <input type="text" id="admin_note" name="admin_note" maxlength="255" class="form-control" placeholder="Ví dụ: Thiết bị đã được đặt cho buổi thi">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button class="btn btn-danger">Từ chối phiếu</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('rejectModal').addEventListener('show.bs.modal', (e) => {
        const btn = e.relatedTarget;
        document.getElementById('rejectForm').action = btn.dataset.action;
        document.querySelector('#rejectModalLabel span').textContent = '#' + btn.dataset.id;
    });
</script>
@endpush
