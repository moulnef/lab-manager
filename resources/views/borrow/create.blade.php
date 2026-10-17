@extends('layouts.app')

@section('title', 'Gửi yêu cầu mượn máy')

@section('content')
    <h1 class="h4 mb-3">Gửi yêu cầu mượn máy</h1>

    <div id="result"></div>

    {{-- novalidate: dùng validate JS bên dưới thay cho popup mặc định --}}
    <form id="borrow-form" novalidate>
        @csrf

        <div class="mb-3">
            <label for="device_id" class="form-label">Thiết bị</label>
            <select id="device_id" name="device_id" class="form-select" required>
                <option value="">-- Chọn thiết bị --</option>
                @foreach ($devices as $d)
                    @if ($d['status'] === 'available')
                        <option value="{{ $d['id'] }}"
                            {{ (string) $selectedId === (string) $d['id'] ? 'selected' : '' }}>
                            {{ $d['name'] }} ({{ $d['type'] }})
                        </option>
                    @endif
                @endforeach
            </select>
            <div class="invalid-feedback">Vui lòng chọn thiết bị.</div>
        </div>

        <button type="submit" id="submit-btn" class="btn btn-primary w-100">Gửi yêu cầu</button>
    </form>

    <a href="{{ url('/devices') }}" class="d-block text-center mt-3">&larr; Quay lại danh sách</a>
@endsection

@push('scripts')
<script>
    // API mượn máy của Loan: POST /api/devices/borrow, nhận { device_id }
    const BORROW_URL = '/api/devices/borrow';

    const form = document.getElementById('borrow-form');
    const device = document.getElementById('device_id');
    const btn = document.getElementById('submit-btn');
    const result = document.getElementById('result');

    function showMessage(type, text) {
        result.innerHTML = '<div class="alert alert-' + type + '" role="alert"></div>';
        result.firstChild.textContent = text; // textContent: không chèn HTML lạ
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        result.innerHTML = '';

        // Validate phía client trước khi gửi lên server
        const ok = device.value !== '';
        device.classList.toggle('is-invalid', !ok);
        if (!ok) { device.focus(); return; }

        btn.disabled = true;
        btn.textContent = 'Đang gửi...';
        try {
            const res = await fetch(BORROW_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ device_id: device.value }),
            });
            const data = await res.json().catch(() => ({}));

            if (res.ok) {
                showMessage('success', data.message || 'Gửi yêu cầu thành công.');
                device.querySelector('option[value="' + device.value + '"]')?.remove();
                device.value = '';
            } else {
                showMessage('danger', data.error || data.message || 'Không thể mượn thiết bị này.');
            }
        } catch (err) {
            showMessage('danger', 'Không kết nối được máy chủ. Thử lại sau.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Gửi yêu cầu';
        }
    });

    device.addEventListener('change', () => device.classList.remove('is-invalid'));
</script>
@endpush
