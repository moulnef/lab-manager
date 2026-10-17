@props(['id', 'name', 'type', 'status'])

<div class="col-12 col-sm-6 col-lg-4">
    <div class="card h-100 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title mb-0">{{ $name }}</h5>
                @if ($status === 'available')
                    <span class="badge text-bg-success">Đang rảnh</span>
                @elseif ($status === 'maintenance')
                    <span class="badge text-bg-warning">Bảo trì</span>
                @else
                    <span class="badge text-bg-secondary">Đang mượn</span>
                @endif
            </div>
            <p class="card-text text-muted">{{ $type }}</p>
        </div>
        <div class="card-footer bg-white border-0 pb-3">
            <a href="{{ url('/borrow/create') }}?device={{ $id }}"
               class="btn btn-primary w-100 {{ $status !== 'available' ? 'disabled' : '' }}">
                Mượn máy
            </a>
        </div>
    </div>
</div>
