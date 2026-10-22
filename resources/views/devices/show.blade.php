@extends('layouts.app')

@section('title', $device->name)

@section('content')
    @php
        $img = $device->image_url;
        $labels = ['available' => ['success', 'Đang rảnh'], 'borrowed' => ['secondary', 'Đang mượn'], 'maintenance' => ['warning', 'Bảo trì']];
        [$color, $text] = $labels[$device->status] ?? ['light', $device->status];
    @endphp

    <a href="{{ route('user.devices') }}" class="d-inline-block mb-3">&larr; Quay lại danh sách</a>

    <div class="row g-4">
        <div class="col-md-5">
            <img src="{{ $img }}" alt="{{ $device->name }}" class="img-fluid rounded shadow-sm w-100" style="max-height:340px;object-fit:cover">
        </div>
        <div class="col-md-7">
            <h1 class="h3">{{ $device->name }}</h1>
            <span class="badge text-bg-{{ $color }} mb-3">{{ $text }}</span>
            <dl class="row mb-4">
                <dt class="col-4">Danh mục</dt><dd class="col-8">{{ $device->category->name ?? '—' }}</dd>
                <dt class="col-4">Loại</dt><dd class="col-8">{{ $device->type }}</dd>
                <dt class="col-4">Mã thiết bị</dt><dd class="col-8">#{{ $device->id }}</dd>
            </dl>

            @if ($device->status === 'available')
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Gửi yêu cầu mượn</h2>
                        <form id="borrow-form" method="POST" action="{{ route('user.borrow.store') }}" novalidate>
                            @csrf
                            <input type="hidden" name="device_id" value="{{ $device->id }}">

                            @error('device_id')<div class="alert alert-danger py-2">{{ $message }}</div>@enderror

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label for="borrow_date" class="form-label">Ngày mượn</label>
                                    <input type="date" id="borrow_date" name="borrow_date" value="{{ old('borrow_date') }}"
                                           class="form-control @error('borrow_date') is-invalid @enderror" required>
                                    <div class="invalid-feedback">@error('borrow_date') {{ $message }} @else Ngày mượn không được trước hôm nay. @enderror</div>
                                </div>
                                <div class="col-6">
                                    <label for="return_date" class="form-label">Ngày trả</label>
                                    <input type="date" id="return_date" name="return_date" value="{{ old('return_date') }}"
                                           class="form-control @error('return_date') is-invalid @enderror" required>
                                    <div class="invalid-feedback">@error('return_date') {{ $message }} @else Ngày trả phải từ ngày mượn trở đi. @enderror</div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Gửi yêu cầu</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-secondary">Thiết bị này hiện không thể mượn. Vui lòng chọn thiết bị khác.</div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const form = document.getElementById('borrow-form');
    if (form) {
        const borrow = document.getElementById('borrow_date');
        const ret = document.getElementById('return_date');
        const today = new Date().toISOString().split('T')[0];
        borrow.min = today;
        ret.min = today;

        function setValid(input, ok) {
            input.classList.toggle('is-invalid', !ok);
            return ok;
        }
        function validate() {
            const okBorrow = setValid(borrow, borrow.value !== '' && borrow.value >= today);
            const okReturn = setValid(ret, ret.value !== '' && ret.value >= borrow.value);
            return okBorrow && okReturn;
        }
        form.addEventListener('submit', e => { if (!validate()) e.preventDefault(); });
        [borrow, ret].forEach(el => el.addEventListener('change', validate));
    }
</script>
@endpush
