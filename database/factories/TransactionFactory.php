<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $borrow = fake()->dateTimeBetween('now', '+5 days');

        return [
            'user_id' => User::factory(),
            'device_id' => Device::factory(),
            'borrow_date' => $borrow,
            'return_date' => (clone $borrow)->modify('+'.fake()->numberBetween(1, 7).' days'),
            'purpose' => fake()->randomElement([
                'Thực hành môn Lập trình mã nguồn mở',
                'Báo cáo đồ án nhóm trên lớp',
                'Làm thí nghiệm IoT',
                'Thuyết trình seminar khoa',
            ]),
            'status' => Transaction::STATUS_PENDING,
        ];
    }
}
