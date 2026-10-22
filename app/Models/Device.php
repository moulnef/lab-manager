<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'status', 'category_id', 'image'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Ảnh nằm trong public/images/... (đi theo git) hoặc ảnh upload ở storage (Huy)
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/no-image.svg');
        }

        return str_starts_with($this->image, 'images/')
            ? asset($this->image)
            : asset('storage/' . $this->image);
    }
}
