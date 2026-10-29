<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Sinh 15 phiếu mượn với đủ các trạng thái,
     * đồng thời cập nhật trạng thái thiết bị cho nhất quán.
     */
    public function run(): void
    {
        $users = User::where('role', User::ROLE_USER)->orderBy('id')->get();
        $devices = Device::where('status', Device::STATUS_AVAILABLE)->orderBy('id')->get()->values();

        // [số ngày lệch so với hôm nay của ngày mượn, số ngày mượn, trạng thái]
        $plan = [
            [-20, 5, Transaction::STATUS_RETURNED],
            [-15, 3, Transaction::STATUS_RETURNED],
            [-12, 7, Transaction::STATUS_RETURNED],
            [-10, 2, Transaction::STATUS_RETURNED],
            [-8, 4, Transaction::STATUS_RETURNED],
            [-6, 3, Transaction::STATUS_REJECTED],
            [-5, 2, Transaction::STATUS_CANCELLED],
            [-4, 6, Transaction::STATUS_APPROVED],
            [-3, 5, Transaction::STATUS_APPROVED],
            [-2, 1, Transaction::STATUS_APPROVED],   // đã quá hạn trả
            [-1, 4, Transaction::STATUS_APPROVED],
            [1, 3, Transaction::STATUS_PENDING],
            [2, 2, Transaction::STATUS_PENDING],
            [3, 5, Transaction::STATUS_PENDING],
            [0, 1, Transaction::STATUS_PENDING],
        ];

        $purposes = [
            'Thực hành môn Lập trình mã nguồn mở',
            'Báo cáo đồ án nhóm trên lớp',
            'Làm thí nghiệm IoT cho đề tài nghiên cứu',
            'Thuyết trình seminar khoa CNTT',
            'Thực hành cấu hình mạng LAN',
        ];

        foreach ($plan as $i => [$offset, $days, $status]) {
            // Mỗi phiếu dùng 1 thiết bị khác nhau, trải đều qua các danh mục
            $device = $devices[($i * 3) % $devices->count()];
            $borrow = today()->addDays($offset);

            Transaction::create([
                'user_id' => $users[$i % $users->count()]->id,
                'device_id' => $device->id,
                'borrow_date' => $borrow,
                'return_date' => $borrow->copy()->addDays($days),
                'purpose' => $purposes[$i % count($purposes)],
                'status' => $status,
                'approved_at' => in_array($status, [Transaction::STATUS_APPROVED, Transaction::STATUS_RETURNED])
                    ? $borrow->copy()->setTime(8, 30) : null,
                'returned_at' => $status === Transaction::STATUS_RETURNED
                    ? $borrow->copy()->addDays($days)->setTime(16, 0) : null,
                'admin_note' => $status === Transaction::STATUS_REJECTED ? 'Thiết bị đã được đặt cho buổi thi.' : null,
                'created_at' => $borrow->copy()->subDay()->setTime(9, 0),
                'updated_at' => now(),
            ]);

            if ($status === Transaction::STATUS_APPROVED) {
                $device->update(['status' => Device::STATUS_BORROWED]);
            }
        }
    }
}
