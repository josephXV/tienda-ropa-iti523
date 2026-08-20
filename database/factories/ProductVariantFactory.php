<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'talla' => fake()->randomElement(['S', 'M', 'L', 'XL']),
            'color' => fake()->randomElement(['Morado', 'Lavanda', 'Negro', 'Blanco']),
            'stock' => fake()->numberBetween(5, 30),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####-???')),
        ];
    }

    public function sinStock(): static
    {
        return $this->state(fn () => ['stock' => 0]);
    }

    public function conStock(int $stock): static
    {
        return $this->state(fn () => ['stock' => $stock]);
    }
}
