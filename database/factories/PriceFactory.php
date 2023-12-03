<?php

namespace Database\Factories;

use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Price>
 */
class PriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'datetime' => $this->faker->unique()->dateTimeBetween('-1 day' )->format('Y-m-d H:i:s'),
            'open' => $this->faker->randomFloat(4, 0, 100),
            'high' => $this->faker->randomFloat(4, 0, 100),
            'low' => $this->faker->randomFloat(4, 0, 100),
            'close' => $this->faker->randomFloat(4, 0, 100),
            'volume' => $this->faker->randomNumber(),
        ];
    }
}
