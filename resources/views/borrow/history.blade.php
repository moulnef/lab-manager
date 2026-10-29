@extends('layouts.app')

@section('title', 'Lịch sử mượn của tôi')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h1 class="h4 fw-bold mb-1">Lịch sử mượn của tôi</h1>
            <p class="text-muted small mb-0">Theo dõi trạng thái các yêu cầu mượn thiết bị của bạn.</p>
        </div>
        <a href="{{ route('borrow.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Yêu cầu mới</a>
    </div>

    {{-- Lọc theo trạng thái --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('borrow.history') }}" class="btn btn-sm {{ ! $status ? 'btn-dark' : 'btn-outline-secondary' }}">
            Tất cả <span class="badge bg-light text-dark ms-1">{{ $counts->sum() }}</span>
        </a>
        @foreach (\App\Models\Transaction::STATUSES as $value => $label)
            <a href="{{ route('borrow.history', ['status' => $value]) }}"
               class="btn btn-sm {{ $status === $value ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ $label }} <span class="badge bg-light text-dark ms-1">{{ $counts[$value] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    @if ($transactions->isEmpty())
        <div class="card empty-state">
            <i class="bi bi-journal-x"></i>
            Chưa có yêu cầu mượn nào. <a href="{{ route('devices.index') }}">Xem danh sách thiết bị</a> để bắt đầu.
        </div>
    @else
        <div class="vstack gap-3">
            @foreach ($transactions as $t)
                <div class="card shadow-sm">
                    <div class="card-body d-flex flex-column flex-md-row gap-3 align-items-md-center">
                        <div class="d-flex gap-3 align-items-center flex-grow-1 min-w-0">
                            <x-device-thumb :device="$t->device" size="sm" />
                            <div class="min-w-0">
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <span class="text-muted small">#{{ $t->id }}</span>
                                    <x-status-badge :model="$t" />
                                    @if ($t->isOverdue())
                                        <span class="badge-soft danger"><i class="bi bi-alarm me-1"></i>Quá hạn</span>
                                    @endif
                                </div>
                                <div class="fw-semibold text-truncate">
                                    @if ($t->device->trashed())
                                        {{ $t->device->name }}
                                    @else
                                        <a href="{{ route('devices.show', $t->device) }}" class="text-reset">{{ $t->device->name }}</a>
                                    @endif
                                </div>
                                <div class="small text-muted">
                                    <i class="bi bi-calendar-event me-1"></i>{{ $t->borrow_date->format('d/m/Y') }} → {{ $t->return_date->format('d/m/Y') }}
                                    · Gửi {{ $t->created_at->diffForHumans() }}
                                </div>
                                @if ($t->purpose)
                                    <div class="small text-muted text-truncate">Mục đích: {{ $t->purpose }}</div>
                                @endif
                                @if ($t->admin_note)
                                    <div class="small text-danger">Ghi chú quản trị: {{ $t->admin_note }}</div>
                                @endif
                                @if ($t->returned_at)
                                    <div class="small text-success">Đã trả lúc {{ $t->returned_at->format('H:i d/m/Y') }}</div>
                                @endif
                            </div>
                        </div>

                        @if ($t->status === \App\Models\Transaction::STATUS_PENDING)
                            <form action="{{ route('borrow.cancel', $t) }}" method="POST"
                                  onsubmit="return confirm('Bạn chắc chắn muốn hủy yêu cầu #{{ $t->id }}?')">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-x-circle me-1"></i>Hủy yêu cầu</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">{{ $transactions->links() }}</div>
    @endif
@endsection

@push('styles')
<style>.min-w-0 { min-width: 0; }</style>
@endpush
