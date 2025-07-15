<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

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
        $costPrice = $this->faker->numberBetween(5000, 50000);
        $price = $costPrice * $this->faker->randomFloat(2, 1.2, 1.5); // Margin 20%-50%

        return [
            'name' => $this->faker->words(3, true),
            'category_id' => Category::factory(),
            'price' => round($price / 100) * 100, // Bulatkan ke ratusan terdekat
            'cost_price' => $costPrice,
            'stock' => $this->faker->numberBetween(10, 200),
            'barcode' => $this->faker->unique()->ean13(),
        ];
    }
}
