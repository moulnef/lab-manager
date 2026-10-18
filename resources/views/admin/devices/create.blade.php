@extends('layouts.admin')

@section('title', 'Thêm thiết bị mới')
@section('page-title', 'Thêm Thiết bị mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Nhập thông tin thiết bị
                </h6>
                <a href="{{ route('admin.devices.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Có lỗi xảy ra trong quá trình nhập liệu:</strong>
                        <ul class="mb-0 mt-2 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.devices.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Tên thiết bị <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Ví dụ: Máy chiếu Sony VPL-EX570, MacBook Air M2..."
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="form-text">Tên hiển thị đầy đủ của thiết bị phòng Lab.</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label fw-semibold">
                            Chủng loại thiết bị <span class="text-danger">*</span>
                        </label>
                        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">-- Chọn chủng loại --</option>
                            <option value="Máy chiếu" {{ old('type') === 'Máy chiếu' ? 'selected' : '' }}>Máy chiếu</option>
                            <option value="Laptop" {{ old('type') === 'Laptop' ? 'selected' : '' }}>Laptop</option>
                            <option value="Micro" {{ old('type') === 'Micro' ? 'selected' : '' }}>Micro</option>
                            <option value="Bộ phát Wifi" {{ old('type') === 'Bộ phát Wifi' ? 'selected' : '' }}>Bộ phát Wifi</option>
                            <option value="Thiết bị đo" {{ old('type') === 'Thiết bị đo' ? 'selected' : '' }}>Thiết bị đo</option>
                            <option value="Khác" {{ old('type') === 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label fw-semibold">
                            Trạng thái ban đầu <span class="text-danger">*</span>
                        </label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="available" {{ old('status', 'available') === 'available' ? 'selected' : '' }}>
                                Đang rảnh (Sẵn sàng cho mượn)
                            </option>
                            <option value="borrowed" {{ old('status') === 'borrowed' ? 'selected' : '' }}>
                                Đang mượn
                            </option>
                            <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>
                                Đang bảo trì
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-3">
                        <a href="{{ route('admin.devices.index') }}" class="btn btn-light border px-4">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Lưu thiết bị mới
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
