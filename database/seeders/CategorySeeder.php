<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /** 6 danh mục thiết bị phòng Lab. */
    public const CATEGORIES = [
        ['name' => 'Laptop & Máy tính', 'icon' => 'laptop', 'description' => 'Laptop, máy tính bàn mini phục vụ thực hành lập trình.'],
        ['name' => 'Máy chiếu & Trình chiếu', 'icon' => 'projector', 'description' => 'Máy chiếu, màn chiếu, bút trình chiếu cho buổi báo cáo.'],
        ['name' => 'Thiết bị mạng', 'icon' => 'router', 'description' => 'Router, switch, access point cho bài thực hành mạng máy tính.'],
        ['name' => 'Vi điều khiển & IoT', 'icon' => 'cpu', 'description' => 'Arduino, Raspberry Pi, ESP32 và các bộ kit cảm biến.'],
        ['name' => 'Thiết bị đo lường', 'icon' => 'speedometer2', 'description' => 'Đồng hồ vạn năng, máy hiện sóng, nguồn DC.'],
        ['name' => 'Âm thanh & Ghi hình', 'icon' => 'mic', 'description' => 'Micro, loa, webcam, máy quay phục vụ hội thảo.'],
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $category) {
            Category::create($category);
        }
    }
}
