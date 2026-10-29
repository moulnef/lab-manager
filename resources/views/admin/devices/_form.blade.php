{{-- Form dùng chung cho Thêm / Sửa thiết bị (có upload ảnh) --}}
@csrf
<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="row g-3">
            <div class="col-sm-5">
                <label for="code" class="form-label">Mã thiết bị <span class="text-danger">*</span></label>
                <input type="text" id="code" name="code" value="{{ old('code', $device->code) }}"
                       class="form-control text-uppercase @error('code') is-invalid @enderror" placeholder="LAB-LT-010" required>
                @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-7">
                <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $device->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <label for="name" class="form-label">Tên thiết bị <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $device->name) }}"
                       class="form-control @error('name') is-invalid @enderror" placeholder="Ví dụ: Laptop Dell Latitude 5420" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach (\App\Models\Device::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $device->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">Trạng thái "Đang mượn" được hệ thống tự cập nhật khi duyệt / trả phiếu mượn.</div>
            </div>
            <div class="col-12">
                <label for="description" class="form-label">Mô tả</label>
                <textarea id="description" name="description" rows="4"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $device->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <label for="image" class="form-label">Ảnh thiết bị</label>
        <div class="card overflow-hidden mb-2">
            <img id="image-preview" src="{{ $device->image_url }}" alt="Xem trước ảnh"
                 class="device-thumb {{ $device->image_url ? '' : 'd-none' }}">
            <div id="image-placeholder" class="device-thumb {{ $device->image_url ? 'd-none' : '' }}"><i class="bi bi-image"></i></div>
        </div>
        <input type="file" id="image" name="image" accept="image/png,image/jpeg,image/webp"
               class="form-control @error('image') is-invalid @enderror">
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">JPG, PNG hoặc WEBP, tối đa 2MB.</div>
        @if ($device->image)
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" value="1" id="remove_image" name="remove_image">
                <label class="form-check-label small" for="remove_image">Xóa ảnh hiện tại</label>
            </div>
        @endif
    </div>
</div>

<hr class="my-4">
<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('admin.devices.index') }}" class="btn btn-outline-secondary">Hủy</a>
    <button class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Lưu thiết bị</button>
</div>

@push('scripts')
<script>
    // Xem trước ảnh trước khi upload
    document.getElementById('image').addEventListener('change', (e) => {
        const file = e.target.files[0];
        const img = document.getElementById('image-preview');
        const ph = document.getElementById('image-placeholder');
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('Ảnh không được lớn hơn 2MB.');
            e.target.value = '';
            return;
        }
        img.src = URL.createObjectURL(file);
        img.classList.remove('d-none');
        ph.classList.add('d-none');
    });
</script>
@endpush
