{{-- Form dùng chung cho Thêm / Sửa danh mục --}}
@csrf
<div class="mb-3">
    <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
           class="form-control @error('name') is-invalid @enderror" placeholder="Ví dụ: Thiết bị mạng" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="icon" class="form-label">Icon (Bootstrap Icons)</label>
    <div class="input-group">
        <span class="input-group-text bg-white"><i class="bi bi-{{ old('icon', $category->icon) ?: 'box' }}" id="icon-preview"></i></span>
        <input type="text" id="icon" name="icon" value="{{ old('icon', $category->icon) }}"
               class="form-control @error('icon') is-invalid @enderror" placeholder="laptop, router, cpu, mic...">
        @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-text">Tra cứu tên icon tại <a href="https://icons.getbootstrap.com" target="_blank" rel="noopener">icons.getbootstrap.com</a>.</div>
</div>

<div class="mb-4">
    <label for="description" class="form-label">Mô tả</label>
    <textarea id="description" name="description" rows="3"
              class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Hủy</a>
    <button class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Lưu danh mục</button>
</div>

@push('scripts')
<script>
    document.getElementById('icon').addEventListener('input', e => {
        document.getElementById('icon-preview').className = 'bi bi-' + (e.target.value.trim() || 'box');
    });
</script>
@endpush
