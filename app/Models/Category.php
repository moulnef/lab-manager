<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'description'];

    /**
     * 1 danh mục có nhiều thiết bị (One-to-Many).
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Tên icon Bootstrap Icons, mặc định là "box".
     */
    public function getIconClassAttribute(): string
    {
        return 'bi-'.($this->icon ?: 'box');
    }
}
