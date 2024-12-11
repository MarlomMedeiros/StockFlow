<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->words(2, true)),
            'code' => strtoupper($this->faker->unique()->bothify('PROD-####')),
            'description' => $this->faker->optional()->sentence(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'supplier_id' => Supplier::inRandomOrder()->first()->id ?? Supplier::factory(),
            'cost_price' => $this->faker->randomFloat(2, 10, 1000),
            'sale_price' => function (array $attributes) {
                return $attributes['cost_price'] * $this->faker->randomFloat(2, 1.1, 2.0);
            },
            'minimum_stock' => $this->faker->numberBetween(0, 100),
            'expiration_date' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}
