{{-- Ảnh thiết bị; nếu chưa có ảnh thì hiển thị icon của danh mục. --}}
@props(['device', 'size' => null])

@if ($device->image_url)
    <img src="{{ $device->image_url }}" alt="{{ $device->name }}" loading="lazy"
         {{ $attributes->merge(['class' => 'device-thumb '.$size]) }}>
@else
    <div {{ $attributes->merge(['class' => 'device-thumb '.$size]) }} aria-hidden="true">
        <i class="bi {{ $device->category?->icon_class ?? 'bi-box' }}"></i>
    </div>
@endif
