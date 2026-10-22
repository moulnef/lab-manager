<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Device;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $counters = [];

        foreach (Device::orderBy('id')->get() as $i => $device) {
            $category = Category::firstOrCreate(['name' => $device->type]);

            $counters[$device->type] = ($counters[$device->type] ?? 0) + 1;
            $device->category_id = $category->id;
            $device->image = $this->imageFor($category->name);
            $device->name = $device->type . ' ' . str_pad($counters[$device->type], 2, '0', STR_PAD_LEFT);
            // Phần lớn máy rảnh để demo dễ: cứ 5 máy có 1 máy đang mượn, 11 máy có 1 máy bảo trì
            $device->status = ($i + 1) % 11 === 0 ? 'maintenance' : (($i + 1) % 5 === 0 ? 'borrowed' : 'available');
            $device->save();
        }
    }

    // Ảnh theo danh mục: public/images/devices/<tên-không-dấu>.jpg|jpeg|png|webp
    // Ví dụ: "Máy chiếu" -> may-chieu.jpg. Không có file thì để trống (hiện ảnh mặc định).
    private function imageFor(string $categoryName): ?string
    {
        $slug = Str::slug($categoryName);

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            if (file_exists(public_path("images/devices/{$slug}.{$ext}"))) {
                return "images/devices/{$slug}.{$ext}";
            }
        }

        return null;
    }
}
