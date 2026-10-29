{{--
    Component Flash Message: hiển thị thông báo sau khi Thêm/Sửa/Xóa.
    Dùng: <x-flash-message />  (đọc session 'success', 'error', 'warning', 'info')
--}}
@php
    $types = [
        'success' => ['alert-success', 'bi-check-circle-fill'],
        'error' => ['alert-danger', 'bi-exclamation-octagon-fill'],
        'warning' => ['alert-warning', 'bi-exclamation-triangle-fill'],
        'info' => ['alert-info', 'bi-info-circle-fill'],
    ];
@endphp

<div class="flash-stack">
    @foreach ($types as $key => [$class, $icon])
        @if (session($key))
            <div class="alert {{ $class }} alert-dismissible fade show shadow-sm d-flex align-items-start" role="alert" data-flash>
                <i class="bi {{ $icon }} me-2 mt-1"></i>
                <div class="flex-grow-1">{{ session($key) }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif
    @endforeach

</div>
