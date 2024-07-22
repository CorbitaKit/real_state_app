<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'province' => rand(1, 10),
            'city' => rand(1, 10),
            'barangay' => rand(1, 10),
            'phase' => fake()->address,
            'purok' => fake()->address,

        ];
    }
}
