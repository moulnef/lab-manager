@props(['device'])

@php
    $img = $device->image_url;
    $labels = [
        'available' => ['success', 'Đang rảnh'],
        'borrowed' => ['secondary', 'Đang mượn'],
        'maintenance' => ['warning', 'Bảo trì'],
    ];
    [$color, $text] = $labels[$device->status] ?? ['light', $device->status];
@endphp

<div class="col-12 col-sm-6 col-lg-4">
    <div class="card h-100 shadow-sm">
        <img src="{{ $img }}" class="card-img-top" alt="{{ $device->name }}" style="height:180px;object-fit:cover">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                <h5 class="card-title mb-0">{{ $device->name }}</h5>
                <span class="badge text-bg-{{ $color }}">{{ $text }}</span>
            </div>
            <p class="text-muted mb-0">{{ $device->category->name ?? $device->type }}</p>
        </div>
        <div class="card-footer bg-white border-0 pb-3">
            <a href="{{ route('user.devices.show', $device) }}" class="btn btn-outline-primary w-100">Xem chi tiết</a>
        </div>
    </div>
</div>
