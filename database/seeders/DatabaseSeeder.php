<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * php artisan migrate:fresh --seed
     * Thứ tự: Người dùng → Danh mục → Thiết bị → Phiếu mượn.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,        // 2 tài khoản chuẩn + 6 sinh viên mẫu
            CategorySeeder::class,    // 6 danh mục
            DeviceSeeder::class,      // 50 thiết bị
            TransactionSeeder::class, // 15 phiếu mượn
        ]);
    }
}
