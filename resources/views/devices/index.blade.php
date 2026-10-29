@extends('layouts.app')

@section('title', 'Danh sách thiết bị')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2 mb-3">
        <div>
            <h1 class="h4 fw-bold mb-1">Danh sách thiết bị</h1>
            <p class="text-muted small mb-0">Gõ từ khóa hoặc chọn bộ lọc – kết quả cập nhật ngay không cần tải lại trang.</p>
        </div>
    </div>

    {{-- Form tìm kiếm: vẫn hoạt động khi tắt JavaScript (GET thông thường) --}}
    <form id="filter-form" action="{{ route('devices.index') }}" method="GET" class="card card-body shadow-sm mb-4">
        <div class="row g-2">
            <div class="col-12 col-md-5">
                <label for="keyword" class="visually-hidden">Từ khóa</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="search" id="keyword" name="keyword" value="{{ $filters['keyword'] ?? '' }}"
                           class="form-control" placeholder="Tên hoặc mã thiết bị..." autocomplete="off">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <label for="category_id" class="visually-hidden">Danh mục</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label for="status" class="visually-hidden">Trạng thái</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Mọi trạng thái</option>
                    @foreach (\App\Models\Device::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i>Lọc</button>
                <a href="{{ route('devices.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc" id="reset-filter"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>

    <div id="device-results" aria-live="polite">
        @include('devices._list')
    </div>
@endsection

@push('scripts')
<script>
/**
 * Lọc nhanh bằng AJAX (Fetch API):
 *  - Gõ từ khóa (debounce 350ms) hoặc đổi bộ lọc → gọi lại /devices?... với header X-Requested-With
 *  - Server trả về HTML của phần danh sách (devices/_list) → thay vào #device-results
 *  - history.replaceState giữ URL ?keyword=...&category_id=... để F5 / chia sẻ link vẫn đúng
 *  - Bấm link phân trang cũng được tải bằng AJAX và giữ nguyên bộ lọc
 */
(() => {
    const form = document.getElementById('filter-form');
    const results = document.getElementById('device-results');
    let timer, controller;

    function buildUrl() {
        const params = new URLSearchParams();
        new FormData(form).forEach((value, key) => { if (String(value).trim() !== '') params.set(key, value); });
        const qs = params.toString();
        return form.action + (qs ? '?' + qs : '');
    }

    async function load(url) {
        controller?.abort();
        controller = new AbortController();
        results.classList.add('loading');
        try {
            const res = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
                signal: controller.signal,
            });
            if (!res.ok) throw new Error(res.status);
            results.innerHTML = await res.text();
            history.replaceState(null, '', url);
        } catch (err) {
            if (err.name !== 'AbortError') {
                results.innerHTML = '<div class="alert alert-danger">Không tải được danh sách thiết bị. Vui lòng thử lại.</div>';
            }
        } finally {
            results.classList.remove('loading');
        }
    }

    form.keyword.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => load(buildUrl()), 350);
    });
    form.category_id.addEventListener('change', () => load(buildUrl()));
    form.status.addEventListener('change', () => load(buildUrl()));
    form.addEventListener('submit', (e) => { e.preventDefault(); load(buildUrl()); });

    document.getElementById('reset-filter').addEventListener('click', (e) => {
        e.preventDefault();
        form.reset();
        form.keyword.value = '';
        form.category_id.value = '';
        form.status.value = '';
        load(form.action);
    });

    // Phân trang bằng AJAX (link vẫn chứa đủ ?keyword=...&page=...)
    results.addEventListener('click', (e) => {
        const link = e.target.closest('.pagination a');
        if (!link) return;
        e.preventDefault();
        load(link.href).then(() => results.scrollIntoView({ behavior: 'smooth', block: 'start' }));
    });
})();
</script>
@endpush
