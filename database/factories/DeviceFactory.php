<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'code' => 'LAB-'.strtoupper(fake()->unique()->bothify('??-####')),
            'name' => fake()->randomElement([
                'Laptop Dell Latitude 5420', 'Máy chiếu Epson EB-X51', 'Arduino Uno R3',
                'Router Cisco ISR 4321', 'Đồng hồ vạn năng Fluke 17B+', 'Micro không dây Shure BLX24',
            ]),
            'description' => fake()->sentence(12),
            'image' => null,
            'status' => Device::STATUS_AVAILABLE,
        ];
    }

    public function borrowed(): static
    {
        return $this->state(fn () => ['status' => Device::STATUS_BORROWED]);
    }

    public function maintenance(): static
    {
        return $this->state(fn () => ['status' => Device::STATUS_MAINTENANCE]);
    }
}
