<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 2 tài khoản chuẩn dùng để demo / chấm bài
        User::factory()->admin()->create([
            'name' => 'Quản trị viên',
            'email' => 'admin@gmail.com',
            'password' => '12345678',
        ]);

        User::factory()->create([
            'name' => 'Nguyễn Văn An',
            'email' => 'user@gmail.com',
            'password' => '12345678',
        ]);

        // Một số sinh viên mẫu để phiếu mượn đa dạng hơn
        $students = [
            'Trần Thị Bích', 'Lê Minh Châu', 'Phạm Quốc Dũng',
            'Hoàng Gia Hân', 'Võ Thành Khang', 'Đặng Ngọc Lan',
        ];
        foreach ($students as $i => $name) {
            User::factory()->create([
                'name' => $name,
                'email' => 'sv'.($i + 1).'@gmail.com',
                'password' => '12345678',
            ]);
        }
    }
}
