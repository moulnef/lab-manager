<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_BORROWED = 'borrowed';
    public const STATUS_MAINTENANCE = 'maintenance';

    /** Nhãn tiếng Việt cho từng trạng thái. */
    public const STATUSES = [
        self::STATUS_AVAILABLE => 'Sẵn sàng',
        self::STATUS_BORROWED => 'Đang mượn',
        self::STATUS_MAINTENANCE => 'Bảo trì',
    ];

    protected $fillable = ['category_id', 'code', 'name', 'description', 'image', 'status'];

    // ===== Quan hệ =====

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // ===== Scope dùng cho tìm kiếm / lọc =====

    /**
     * Lọc theo từ khóa (tên hoặc mã), danh mục và trạng thái.
     * Dùng chung cho trang User và trang Admin.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $keyword = trim($filters['keyword'] ?? '');

        return $query
            ->when($keyword !== '', function (Builder $q) use ($keyword) {
                $q->where(function (Builder $sub) use ($keyword) {
                    $sub->where('name', 'like', "%{$keyword}%")
                        ->orWhere('code', 'like', "%{$keyword}%");
                });
            })
            ->when(! empty($filters['category_id']), fn (Builder $q) => $q->where('category_id', $filters['category_id']))
            ->when(! empty($filters['status']) && array_key_exists($filters['status'], self::STATUSES),
                fn (Builder $q) => $q->where('status', $filters['status']));
    }

    // ===== Thuộc tính tiện ích =====

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /** Màu Bootstrap tương ứng trạng thái. */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_AVAILABLE => 'success',
            self::STATUS_BORROWED => 'warning',
            default => 'danger',
        };
    }

    /** URL ảnh thiết bị (null nếu chưa upload). */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }
}
