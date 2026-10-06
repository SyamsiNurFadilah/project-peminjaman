<?php

namespace Database\Factories;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Borrowing>
 */
class BorrowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $borrowDate = fake()->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $borrowDate)->modify('+7 days');

        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'borrow_date' => $borrowDate,
            'due_date' => $dueDate,
            'return_date' => null,
            'status' => 'borrowed',
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the borrowing is returned.
     */
    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'returned',
            'return_date' => (clone $attributes['borrow_date'])->modify('+5 days'),
        ]);
    }

    /**
     * Indicate that the borrowing is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'return_date' => null,
        ]);
    }
}
