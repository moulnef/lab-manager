<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      // Tạo 1 tài khoản Admin mẫu để Huy test đăng nhập
        \App\Models\User::factory()->create([
            'name' => 'Quản trị viên',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('12345678'),
        ]);

        // Tạo 5 tài khoản User thường
        \App\Models\User::factory(5)->create();

        // Tạo 30 thiết bị mẫu
        \App\Models\Device::factory(30)->create();
    }
}
