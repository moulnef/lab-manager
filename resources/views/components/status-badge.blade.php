{{-- Badge trạng thái dùng chung cho Thiết bị và Phiếu mượn. Dùng: <x-status-badge :model="$device" /> --}}
@props(['model'])

<span {{ $attributes->merge(['class' => 'badge-soft '.$model->status_color]) }}>{{ $model->status_label }}</span>
