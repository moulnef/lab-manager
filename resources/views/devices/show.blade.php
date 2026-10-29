@extends('layouts.app')

@section('title', $device->name)

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('devices.index') }}">Thiết bị</a></li>
            <li class="breadcrumb-item"><a href="{{ route('devices.index', ['category_id' => $device->category_id]) }}">{{ $device->category->name }}</a></li>
            <li class="breadcrumb-item active text-truncate" aria-current="page">{{ $device->code }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card overflow-hidden shadow-sm">
                <x-device-thumb :device="$device" />
            </div>
        </div>
        <div class="col-lg-6">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="device-code">{{ $device->code }}</span>
                <x-status-badge :model="$device" />
            </div>
            <h1 class="h3 fw-bold">{{ $device->name }}</h1>
            <p class="text-muted"><i class="bi {{ $device->category->icon_class }} me-1"></i>{{ $device->category->name }}</p>

            <p>{{ $device->description ?: 'Chưa có mô tả cho thiết bị này.' }}</p>

            <ul class="list-group list-group-flush mb-4 small">
                <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Mã thiết bị</span><strong>{{ $device->code }}</strong></li>
                <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Tình trạng</span><strong>{{ $device->status_label }}</strong></li>
                <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Số lượt đã được mượn</span><strong>{{ $borrowCount }}</strong></li>
                <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Cập nhật lần cuối</span><strong>{{ $device->updated_at->format('d/m/Y') }}</strong></li>
            </ul>

            @if ($device->isAvailable())
                <a href="{{ route('borrow.create', ['device' => $device->id]) }}" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-box-arrow-in-down me-1"></i>Gửi yêu cầu mượn thiết bị này
                </a>
                @guest
                    <p class="small text-muted text-center mt-2 mb-0">Bạn cần <a href="{{ route('login') }}">đăng nhập</a> để gửi yêu cầu.</p>
                @endguest
            @else
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Thiết bị hiện <strong>{{ mb_strtolower($device->status_label) }}</strong>, tạm thời chưa thể gửi yêu cầu mượn.
                </div>
            @endif
        </div>
    </div>

    @if ($related->isNotEmpty())
        <h2 class="h5 fw-bold mt-5 mb-3">Thiết bị cùng danh mục</h2>
        <div class="row g-3">
            @foreach ($related as $item)
                <x-device-card :device="$item" />
            @endforeach
        </div>
    @endif
@endsection
