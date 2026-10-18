@extends('layouts.admin')

@section('title', 'Chỉnh sửa thiết bị #' . $device->id)
@section('page-title', 'Chỉnh sửa Thiết bị #' . $device->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Cập nhật thông tin thiết bị
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

                <form action="{{ route('admin.devices.update', $device) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Tên thiết bị <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $device->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
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
                            @php
                                $currentType = old('type', $device->type);
                                $types = ['Máy chiếu', 'Laptop', 'Micro', 'Bộ phát Wifi', 'Thiết bị đo', 'Khác'];
                            @endphp
                            @foreach ($types as $t)
                                <option value="{{ $t }}" {{ $currentType === $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label fw-semibold">
                            Trạng thái thiết bị <span class="text-danger">*</span>
                        </label>
                        @php
                            $currentStatus = old('status', $device->status);
                        @endphp
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="available" {{ $currentStatus === 'available' ? 'selected' : '' }}>
                                Đang rảnh (Sẵn sàng cho mượn)
                            </option>
                            <option value="borrowed" {{ $currentStatus === 'borrowed' ? 'selected' : '' }}>
                                Đang mượn
                            </option>
                            <option value="maintenance" {{ $currentStatus === 'maintenance' ? 'selected' : '' }}>
                                Đang bảo trì
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-between align-items-center border-top pt-3">
                        <div class="text-muted small">
                            Khởi tạo ngày: {{ $device->created_at ? $device->created_at->format('d/m/Y H:i') : '---' }}
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.devices.index') }}" class="btn btn-light border px-4">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                                <i class="bi bi-save me-1"></i> Cập nhật thiết bị
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
