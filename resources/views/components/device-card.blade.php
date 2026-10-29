{{--
    Component thẻ thiết bị (Blade Component).
    Dùng: <x-device-card :device="$device" />
--}}
@props(['device'])

<div class="col-12 col-sm-6 col-lg-4">
    <div class="card device-card h-100 shadow-sm">
        <a href="{{ route('devices.show', $device) }}" class="text-decoration-none">
            <x-device-thumb :device="$device" />
        </a>
        <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                <span class="device-code">{{ $device->code }}</span>
                <x-status-badge :model="$device" />
            </div>
            <h3 class="h6 fw-semibold mb-1">
                <a href="{{ route('devices.show', $device) }}" class="text-reset text-decoration-none">{{ $device->name }}</a>
            </h3>
            <p class="small text-muted mb-3">
                <i class="bi {{ $device->category->icon_class }} me-1"></i>{{ $device->category->name }}
            </p>
            <div class="mt-auto d-flex gap-2">
                <a href="{{ route('devices.show', $device) }}" class="btn btn-outline-primary btn-sm flex-fill">Chi tiết</a>
                @if ($device->isAvailable())
                    <a href="{{ route('borrow.create', ['device' => $device->id]) }}" class="btn btn-primary btn-sm flex-fill">
                        <i class="bi bi-box-arrow-in-down me-1"></i>Mượn
                    </a>
                @else
                    <button class="btn btn-secondary btn-sm flex-fill" disabled>Không khả dụng</button>
                @endif
            </div>
        </div>
    </div>
</div>
