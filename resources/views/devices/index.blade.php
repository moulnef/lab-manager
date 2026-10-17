@extends('layouts.app')

@section('title', 'Danh sách thiết bị')

@section('content')
    <h1 class="h4 mb-3">Danh sách thiết bị</h1>

    {{-- Tìm kiếm + lọc (Ngày 3 sẽ gắn JS vào) --}}
    <div class="row g-2 mb-3">
        <div class="col-12 col-sm-8">
            <input type="search" id="search" class="form-control" placeholder="Tìm theo tên thiết bị...">
        </div>
        <div class="col-12 col-sm-4">
            <select id="status-filter" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="available">Đang rảnh</option>
                <option value="borrowed">Đang mượn</option>
                <option value="maintenance">Bảo trì</option>
            </select>
        </div>
    </div>

    <div class="row g-3" id="device-list">
        @foreach ($devices as $d)
            <x-device-card :id="$d['id']" :name="$d['name']" :type="$d['type']" :status="$d['status']" />
        @endforeach
    </div>
@endsection

@push('scripts')
<script>
    // API của Loan: GET /api/devices (trả mảng JSON). Muốn dùng dữ liệu giả thì đổi lại '/devices.json'.
    const API_URL = '/api/devices';

    const list = document.getElementById('device-list');
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status-filter');

    // Phải giống markup của components/device-card.blade.php
    function cardHtml(d) {
        const available = d.status === 'available';
        const badge = {
            available: ['text-bg-success', 'Đang rảnh'],
            borrowed: ['text-bg-secondary', 'Đang mượn'],
            maintenance: ['text-bg-warning', 'Bảo trì'],
        }[d.status] || ['text-bg-light', d.status];
        return `
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">${d.name}</h5>
                        <span class="badge ${badge[0]}">${badge[1]}</span>
                    </div>
                    <p class="card-text text-muted">${d.type}</p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <a href="/borrow/create?device=${d.id}" class="btn btn-primary w-100 ${available ? '' : 'disabled'}">Mượn máy</a>
                </div>
            </div>
        </div>`;
    }

    async function loadDevices() {
        const params = new URLSearchParams({
            q: searchInput.value.trim(),
            status: statusSelect.value,
        });
        try {
            // File JSON tĩnh bỏ qua params; ta lọc phía client tạm thời.
            const res = await fetch(`${API_URL}?${params}`);
            if (!res.ok) throw new Error(res.status);
            let data = await res.json();
            data = data.data ?? data;

            const q = searchInput.value.trim().toLowerCase();
            const s = statusSelect.value;
            data = data.filter(d =>
                d.name.toLowerCase().includes(q) && (!s || d.status === s));

            list.innerHTML = data.length
                ? data.map(cardHtml).join('')
                : '<p class="text-muted">Không có thiết bị phù hợp.</p>';
        } catch (e) {
            list.innerHTML = '<p class="text-danger">Không tải được danh sách. Thử lại sau.</p>';
        }
    }

    let timer;
    searchInput.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(loadDevices, 300);
    });
    statusSelect.addEventListener('change', loadDevices);
</script>
@endpush
