<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BookCopy>
 */
class BookCopyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'barcode' => 'RO-'.Str::upper(Str::random(10)),
            'status' => BookCopy::STATUS_AVAILABLE,
            'location' => fake()->randomElement(['Main Hall A1', 'Main Hall B2', 'Digital Reading Wing', 'Archive Stack C']),
            'condition' => fake()->randomElement(['new', 'good', 'worn']),
            'acquisition_date' => fake()->dateTimeBetween('-5 years'),
        ];
    }

    /**
     * Indicate that the copy is borrowed.
     */
    public function borrowed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => BookCopy::STATUS_BORROWED,
        ]);
    }
}
