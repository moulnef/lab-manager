{{-- Partial danh sách thiết bị – dùng chung cho lần tải đầu và cho AJAX --}}
<div class="d-flex justify-content-between align-items-center mb-2 small text-muted">
    <span>
        @if ($devices->total() > 0)
            Hiển thị <strong>{{ $devices->firstItem() }}–{{ $devices->lastItem() }}</strong> / {{ $devices->total() }} thiết bị
        @else
            Không có kết quả
        @endif
    </span>
    @if (request('keyword'))
        <span>Từ khóa: <strong>“{{ request('keyword') }}”</strong></span>
    @endif
</div>

<div class="row g-3">
    @forelse ($devices as $device)
        <x-device-card :device="$device" />
    @empty
        <div class="col-12">
            <div class="card empty-state">
                <i class="bi bi-search"></i>
                Không tìm thấy thiết bị phù hợp. Hãy thử từ khóa khác hoặc bỏ bớt bộ lọc.
            </div>
        </div>
    @endforelse
</div>

@if ($devices->hasPages())
    <nav class="mt-4 d-flex justify-content-center" aria-label="Phân trang">
        {{ $devices->onEachSide(1)->links() }}
    </nav>
@endif
