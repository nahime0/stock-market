<?php

namespace Database\Factories;

use App\Models\Symbol;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Symbol>
 */
class SymbolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'symbol' => $this->faker->unique()->regexify('[A-Z]{3,5}'),
            'name' => $this->faker->company(),
        ];
    }
}
