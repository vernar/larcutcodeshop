<?php

namespace Database\Factories;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
            'title'        => ucfirst($this->faker->words(2, true)),
            'brand_id'     => Brand::query()->inRandomOrder()->value('id'),
            'thumbnail'    => $this->faker->customFile(
                base_path('tests/Fixtures/images/products'),
                'images/products'
            ),
            'price'        => $this->faker->numberBetween(100, 10000000),
            'on_home_page' => $this->faker->boolean(),
            'sorting'      => $this->faker->numberBetween(1, 999),
        ];
    }
}
