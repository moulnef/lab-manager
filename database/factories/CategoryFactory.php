<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Danh mục '.fake()->unique()->numberBetween(1, 9999),
            'icon' => fake()->randomElement(['laptop', 'projector', 'router', 'cpu', 'speedometer2', 'mic']),
            'description' => fake()->sentence(10),
        ];
    }
}
