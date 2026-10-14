<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Thiết bị ' . fake()->word(),
            'type' => fake()->randomElement(['Máy chiếu', 'Laptop', 'Micro', 'Bộ phát Wifi']),
            'status' => fake()->randomElement(['available', 'borrowed', 'maintenance']),
        ];
    }
}
