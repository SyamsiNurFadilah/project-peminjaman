<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Lab', 'Perpustakaan', 'Alat Himpunan'];
        $prefix = ['Lab' => 'INV-LAB', 'Perpustakaan' => 'INV-LIB', 'Alat Himpunan' => 'INV-HMP'];
        $category = fake()->randomElement($categories);
        $totalStock = fake()->numberBetween(2, 15);

        return [
            'item_code' => $prefix[$category].'-'.fake()->unique()->numerify('###'),
            'name' => fake()->words(3, true),
            'category' => $category,
            'total_stock' => $totalStock,
            'available_stock' => $totalStock,
            'status' => 'available',
        ];
    }

    /**
     * Indicate that the item is out of stock.
     */
    public function empty(): static
    {
        return $this->state(fn (array $attributes) => [
            'available_stock' => 0,
            'status' => 'empty',
        ]);
    }

    /**
     * Indicate that the item is in maintenance.
     */
    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'available_stock' => 0,
            'status' => 'maintenance',
        ]);
    }
}
