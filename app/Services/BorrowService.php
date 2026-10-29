<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Lớp dịch vụ xử lý nghiệp vụ Mượn – Trả thiết bị.
 * Mọi thao tác thay đổi đồng thời bảng transactions và devices
 * đều chạy trong DB::transaction để đảm bảo tính toàn vẹn dữ liệu:
 * nếu một bước lỗi, toàn bộ thay đổi được rollback.
 */
class BorrowService
{
    /** Người dùng gửi yêu cầu mượn (trạng thái chờ duyệt). */
    public function request(User $user, array $data): Transaction
    {
        return $user->transactions()->create([
            'device_id' => $data['device_id'],
            'borrow_date' => $data['borrow_date'],
            'return_date' => $data['return_date'],
            'purpose' => $data['purpose'] ?? null,
            'status' => Transaction::STATUS_PENDING,
        ]);
    }

    /** Admin duyệt phiếu: phiếu → "Đang mượn", thiết bị → "Đang mượn". */
    public function approve(Transaction $transaction): Transaction
    {
        return DB::transaction(function () use ($transaction) {
            $transaction = Transaction::whereKey($transaction->id)->lockForUpdate()->firstOrFail();
            $this->ensureStatus($transaction, Transaction::STATUS_PENDING, 'Chỉ có thể duyệt phiếu đang chờ duyệt.');

            // Khóa dòng thiết bị để tránh 2 admin duyệt cùng lúc
            $device = Device::whereKey($transaction->device_id)->lockForUpdate()->first();
            if (! $device) {
                throw new RuntimeException('Thiết bị của phiếu này đã bị xóa, không thể duyệt.');
            }
            if (! $device->isAvailable()) {
                throw new RuntimeException("Thiết bị \"{$device->name}\" hiện không sẵn sàng ({$device->status_label}).");
            }

            $transaction->update([
                'status' => Transaction::STATUS_APPROVED,
                'approved_at' => now(),
            ]);
            $device->update(['status' => Device::STATUS_BORROWED]);

            // Các yêu cầu khác đang chờ cho cùng thiết bị sẽ tự động bị từ chối
            Transaction::where('device_id', $device->id)
                ->where('id', '!=', $transaction->id)
                ->where('status', Transaction::STATUS_PENDING)
                ->update([
                    'status' => Transaction::STATUS_REJECTED,
                    'admin_note' => 'Thiết bị đã được duyệt cho người khác.',
                    'updated_at' => now(),
                ]);

            return $transaction;
        });
    }

    /** Admin từ chối phiếu đang chờ. */
    public function reject(Transaction $transaction, ?string $note = null): Transaction
    {
        $this->ensureStatus($transaction, Transaction::STATUS_PENDING, 'Chỉ có thể từ chối phiếu đang chờ duyệt.');

        $transaction->update([
            'status' => Transaction::STATUS_REJECTED,
            'admin_note' => $note ?: 'Yêu cầu không được chấp thuận.',
        ]);

        return $transaction;
    }

    /** Admin xác nhận trả: phiếu → "Đã trả", thiết bị → "Sẵn sàng". */
    public function markReturned(Transaction $transaction): Transaction
    {
        return DB::transaction(function () use ($transaction) {
            $transaction = Transaction::whereKey($transaction->id)->lockForUpdate()->firstOrFail();
            $this->ensureStatus($transaction, Transaction::STATUS_APPROVED, 'Chỉ có thể xác nhận trả cho phiếu đang mượn.');

            $transaction->update([
                'status' => Transaction::STATUS_RETURNED,
                'returned_at' => now(),
            ]);

            Device::withTrashed()
                ->whereKey($transaction->device_id)
                ->update(['status' => Device::STATUS_AVAILABLE, 'updated_at' => now()]);

            return $transaction;
        });
    }

    /** Người dùng tự hủy yêu cầu của mình khi còn đang chờ duyệt. */
    public function cancel(Transaction $transaction): Transaction
    {
        $this->ensureStatus($transaction, Transaction::STATUS_PENDING, 'Chỉ có thể hủy yêu cầu đang chờ duyệt.');
        $transaction->update(['status' => Transaction::STATUS_CANCELLED]);

        return $transaction;
    }

    private function ensureStatus(Transaction $transaction, string $expected, string $message): void
    {
        if ($transaction->status !== $expected) {
            throw new RuntimeException($message);
        }
    }
}
