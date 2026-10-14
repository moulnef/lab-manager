<?php
namespace App\Services;

use App\Models\Device;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Exception;

class DeviceService
{
    public function borrowDevice($deviceId, $userId)
    {
        // Dùng DB::transaction để đảm bảo nếu lỗi sẽ tự động rollback
        return DB::transaction(function () use ($deviceId, $userId) {
            $device = Device::findOrFail($deviceId);

            if ($device->status !== 'available') {
                throw new Exception('Thiết bị này không sẵn sàng để mượn.');
            }

            // 1. Đổi trạng thái thiết bị thành đang mượn
            $device->update(['status' => 'borrowed']);

            // 2. Ghi lịch sử vào bảng transactions
            return Transaction::create([
                'device_id' => $deviceId,
                'user_id' => $userId,
                'borrow_date' => now(),
                'status' => 'active'
            ]);
        });
    }
}