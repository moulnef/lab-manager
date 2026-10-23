@extends('layouts.app')

@section('title', 'Lịch sử mượn của tôi')

@section('content')
    @php
        $labels = [
            'pending' => ['warning', 'Chờ duyệt'],
            'active' => ['primary', 'Đang mượn'],
            'returned' => ['success', 'Đã trả'],
            'cancelled' => ['secondary', 'Đã hủy'],
        ];
        $fmt = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d/m/Y') : '—';
    @endphp

    <h1 class="h4 mb-3">Lịch sử mượn của tôi</h1>

    @if ($transactions->isEmpty())
        <div class="alert alert-light border">
            Bạn chưa gửi yêu cầu mượn nào. <a href="{{ route('user.devices') }}">Xem danh sách thiết bị</a>
        </div>
    @else
        <div class="table-responsive bg-white rounded shadow-sm">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Thiết bị</th>
                        <th>Ngày mượn</th>
                        <th>Ngày trả</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $t)
                        @php [$color, $text] = $labels[$t->status] ?? ['light', $t->status]; @endphp
                        <tr>
                            <td>{{ $t->id }}</td>
                            <td>
                                @if ($t->device)
                                    <a href="{{ route('user.devices.show', $t->device) }}">{{ $t->device->name }}</a>
                                    <div class="small text-muted">{{ $t->device->category->name ?? $t->device->type }}</div>
                                @else
                                    <span class="text-muted">Thiết bị đã bị xóa</span>
                                @endif
                            </td>
                            <td>{{ $fmt($t->borrow_date) }}</td>
                            <td>{{ $fmt($t->return_date) }}</td>
                            <td><span class="badge text-bg-{{ $color }}">{{ $text }}</span></td>
                            <td class="text-end">
                                @if ($t->status === 'pending')
                                    <form method="POST" action="{{ route('user.borrows.cancel', $t) }}"
                                          onsubmit="return confirm('Hủy yêu cầu mượn này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hủy yêu cầu</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $transactions->links() }}</div>
    @endif
@endsection
