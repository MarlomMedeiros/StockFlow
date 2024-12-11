<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id ?? Product::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'type' => $this->faker->randomElement(['entry', 'exit', 'adjustment']),
            'quantity' => $this->faker->numberBetween(1, 1000),
            'reason' => $this->faker->optional()->sentence(),
        ];
    }
}
