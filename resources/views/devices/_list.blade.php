<div class="row g-3">
    @forelse ($devices as $device)
        <x-device-card :device="$device" />
    @empty
        <p class="text-muted">Không có thiết bị phù hợp.</p>
    @endforelse
</div>
<div class="mt-3">{{ $devices->links() }}</div>
