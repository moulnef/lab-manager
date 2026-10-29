@extends('layouts.app')

@section('title', 'Gửi yêu cầu mượn')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        <h1 class="h4 fw-bold mb-1">Gửi yêu cầu mượn thiết bị</h1>
        <p class="text-muted small mb-4">Yêu cầu sẽ ở trạng thái <span class="badge-soft info">Chờ duyệt</span> cho đến khi quản trị viên xác nhận.</p>

        @if ($devices->isEmpty())
            <div class="card empty-state">
                <i class="bi bi-inboxes"></i>
                Hiện không còn thiết bị nào sẵn sàng. Vui lòng quay lại sau.
            </div>
        @else
        <form action="{{ route('borrow.store') }}" method="POST" id="borrow-form" class="card shadow-sm" novalidate>
            @csrf
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="device_id" class="form-label">Thiết bị <span class="text-danger">*</span></label>
                    <select id="device_id" name="device_id" class="form-select @error('device_id') is-invalid @enderror" required>
                        <option value="">-- Chọn thiết bị đang sẵn sàng --</option>
                        @foreach ($devices->groupBy(fn ($d) => $d->category->name) as $categoryName => $items)
                            <optgroup label="{{ $categoryName }}">
                                @foreach ($items as $device)
                                    <option value="{{ $device->id }}" @selected(old('device_id', $selectedId) == $device->id)>
                                        {{ $device->code }} – {{ $device->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('device_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback">Vui lòng chọn thiết bị.</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="borrow_date" class="form-label">Ngày mượn <span class="text-danger">*</span></label>
                        <input type="date" id="borrow_date" name="borrow_date" min="{{ today()->toDateString() }}"
                               value="{{ old('borrow_date', today()->toDateString()) }}"
                               class="form-control @error('borrow_date') is-invalid @enderror" required>
                        @error('borrow_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Ngày mượn không được ở trong quá khứ.</div>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <label for="return_date" class="form-label">Ngày trả dự kiến <span class="text-danger">*</span></label>
                        <input type="date" id="return_date" name="return_date" min="{{ today()->toDateString() }}"
                               value="{{ old('return_date', today()->addDays(2)->toDateString()) }}"
                               class="form-control @error('return_date') is-invalid @enderror" required>
                        @error('return_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Ngày trả phải lớn hơn hoặc bằng ngày mượn.</div>
                        @enderror
                        <div class="form-text">Tối đa {{ $maxDays }} ngày cho mỗi lần mượn.</div>
                    </div>
                </div>

                <div class="mb-1">
                    <label for="purpose" class="form-label">Mục đích sử dụng <span class="text-danger">*</span></label>
                    <textarea id="purpose" name="purpose" rows="3" maxlength="500" required
                              class="form-control @error('purpose') is-invalid @enderror"
                              placeholder="Ví dụ: Thực hành môn Lập trình mã nguồn mở, báo cáo đồ án nhóm...">{{ old('purpose') }}</textarea>
                    @error('purpose')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback">Vui lòng nhập mục đích sử dụng (ít nhất 5 ký tự).</div>
                    @enderror
                </div>
            </div>
            <div class="card-footer bg-white p-3 d-flex flex-column flex-sm-row gap-2 justify-content-end">
                <a href="{{ route('devices.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary px-4" id="submit-btn">
                    <i class="bi bi-send me-1"></i>Gửi yêu cầu
                </button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Kiểm tra nhanh phía client (server vẫn kiểm tra lại bằng BorrowRequest)
(() => {
    const form = document.getElementById('borrow-form');
    if (!form) return;
    const maxDays = {{ $maxDays }};
    const today = '{{ today()->toDateString() }}';
    const f = form.elements;

    function check() {
        let ok = true;
        const mark = (el, valid, msg) => {
            el.classList.toggle('is-invalid', !valid);
            if (!valid && msg) el.parentElement.querySelector('.invalid-feedback').textContent = msg;
            ok = ok && valid;
        };
        mark(f.device_id, f.device_id.value !== '');
        mark(f.borrow_date, f.borrow_date.value !== '' && f.borrow_date.value >= today, 'Ngày mượn không được ở trong quá khứ.');

        let retOk = f.return_date.value !== '' && f.return_date.value >= f.borrow_date.value;
        let retMsg = 'Ngày trả phải lớn hơn hoặc bằng ngày mượn.';
        if (retOk) {
            const days = (new Date(f.return_date.value) - new Date(f.borrow_date.value)) / 86400000;
            if (days > maxDays) { retOk = false; retMsg = 'Chỉ được mượn tối đa ' + maxDays + ' ngày cho mỗi lần.'; }
        }
        mark(f.return_date, retOk, retMsg);
        mark(f.purpose, f.purpose.value.trim().length >= 5, 'Vui lòng nhập mục đích sử dụng (ít nhất 5 ký tự).');
        return ok;
    }

    f.borrow_date.addEventListener('change', () => { f.return_date.min = f.borrow_date.value; });
    form.addEventListener('submit', (e) => {
        if (!check()) { e.preventDefault(); form.querySelector('.is-invalid')?.focus(); return; }
        document.getElementById('submit-btn').disabled = true;
    });
})();
</script>
@endpush
