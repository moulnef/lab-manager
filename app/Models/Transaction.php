<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING => 'Chờ duyệt',
        self::STATUS_APPROVED => 'Đang mượn',
        self::STATUS_RETURNED => 'Đã trả',
        self::STATUS_REJECTED => 'Bị từ chối',
        self::STATUS_CANCELLED => 'Đã hủy',
    ];

    protected $fillable = [
        'user_id', 'device_id', 'borrow_date', 'return_date', 'purpose',
        'status', 'approved_at', 'returned_at', 'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'return_date' => 'date',
            'approved_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Thiết bị của phiếu mượn – lấy cả thiết bị đã bị xóa mềm
     * để lịch sử mượn không bị mất thông tin.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class)->withTrashed();
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'info',
            self::STATUS_APPROVED => 'warning',
            self::STATUS_RETURNED => 'success',
            self::STATUS_REJECTED => 'danger',
            default => 'secondary',
        };
    }

    /** Phiếu đang mượn nhưng đã quá hạn trả. */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_APPROVED && $this->return_date->lt(today());
    }
}
