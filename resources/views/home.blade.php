@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    {{-- Slider (thay nền gradient bằng ảnh thật: style="background:url('/images/slide1.jpg') center/cover") --}}
    <div id="slider" class="carousel slide mb-4 rounded overflow-hidden shadow-sm" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#slider" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#slider" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#slider" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active" style="height:260px;background:linear-gradient(135deg,#0d6efd,#6610f2)">
                <div class="carousel-caption h-100 d-flex flex-column justify-content-center">
                    <h2>Mượn thiết bị trực tuyến</h2>
                    <p class="mb-0">Chọn thiết bị, chọn ngày, gửi yêu cầu chỉ trong vài bước.</p>
                </div>
            </div>
            <div class="carousel-item" style="height:260px;background:linear-gradient(135deg,#198754,#20c997)">
                <div class="carousel-caption h-100 d-flex flex-column justify-content-center">
                    <h2>Theo dõi tình trạng thiết bị</h2>
                    <p class="mb-0">Biết ngay thiết bị nào đang rảnh, đang mượn hoặc bảo trì.</p>
                </div>
            </div>
            <div class="carousel-item" style="height:260px;background:linear-gradient(135deg,#fd7e14,#dc3545)">
                <div class="carousel-caption h-100 d-flex flex-column justify-content-center">
                    <h2>Quản lý minh bạch</h2>
                    <p class="mb-0">Mọi yêu cầu mượn đều được ghi nhận và duyệt bởi quản trị viên.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#slider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span><span class="visually-hidden">Trước</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#slider" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span><span class="visually-hidden">Sau</span>
        </button>
    </div>

    <div class="row g-3 text-center mb-4">
        <div class="col-4"><div class="bg-white rounded shadow-sm p-3"><div class="fs-3 fw-bold">{{ $total }}</div><div class="text-muted small">Thiết bị</div></div></div>
        <div class="col-4"><div class="bg-white rounded shadow-sm p-3"><div class="fs-3 fw-bold text-success">{{ $available }}</div><div class="text-muted small">Đang rảnh</div></div></div>
        <div class="col-4"><div class="bg-white rounded shadow-sm p-3"><div class="fs-3 fw-bold">{{ $categories }}</div><div class="text-muted small">Danh mục</div></div></div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Thiết bị mới</h2>
        <a href="{{ route('user.devices') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
    </div>
    <div class="row g-3">
        @foreach ($devices as $device)
            <x-device-card :device="$device" />
        @endforeach
    </div>
@endsection
