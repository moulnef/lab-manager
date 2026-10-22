@extends('layouts.app')

@section('title', 'Danh sách thiết bị')

@section('content')
    <h1 class="h4 mb-3">Danh sách thiết bị</h1>

    <form id="filter-form" method="GET" action="{{ route('user.devices') }}" class="row g-2 mb-3">
        <div class="col-12 col-md-5">
            <input type="search" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo tên thiết bị...">
        </div>
        <div class="col-6 col-md-3">
            <select name="category_id" class="form-select">
                <option value="">Tất cả danh mục</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" {{ (string) request('category_id') === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select name="status" class="form-select">
                <option value="">Mọi trạng thái</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Đang rảnh</option>
                <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Đang mượn</option>
                <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
            </select>
        </div>
        <div class="col-12 col-md-1 d-grid">
            <button class="btn btn-primary" type="submit">Lọc</button>
        </div>
    </form>

    <div id="device-list">
        @include('devices._list')
    </div>
@endsection

@push('scripts')
<script>
    // Lọc nhanh bằng AJAX: không tải lại trang, URL vẫn giữ từ khóa để phân trang đúng
    const form = document.getElementById('filter-form');
    const list = document.getElementById('device-list');
    let timer;

    async function refresh() {
        const params = new URLSearchParams(new FormData(form));
        Array.from(params.keys()).forEach(k => { if (!params.get(k)) params.delete(k); });
        const url = form.action + (params.toString() ? '?' + params : '');
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) throw new Error(res.status);
            list.innerHTML = await res.text();
            history.replaceState(null, '', url);
        } catch (e) {
            list.innerHTML = '<p class="text-danger">Không tải được danh sách. Thử lại sau.</p>';
        }
    }

    form.addEventListener('submit', e => { e.preventDefault(); refresh(); });
    form.elements['keyword'].addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(refresh, 300); });
    form.elements['category_id'].addEventListener('change', refresh);
    form.elements['status'].addEventListener('change', refresh);
</script>
@endpush
