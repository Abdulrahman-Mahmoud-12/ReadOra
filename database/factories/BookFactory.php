<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'publisher_id' => Publisher::factory(),
            'title' => $title,
            'subtitle' => fake()->optional()->sentence(4),
            'slug' => Str::slug($title),
            'isbn_10' => fake()->optional()->isbn10(),
            'isbn_13' => fake()->optional()->isbn13(),
            'description' => fake()->paragraphs(3, true),
            'language' => 'en',
            'publication_year' => fake()->numberBetween(1850, 2026),
            'edition' => fake()->optional()->randomElement(['First edition', 'Revised edition', 'Library edition']),
            'page_count' => fake()->numberBetween(120, 900),
            'cover_image_path' => null,
            'average_rating' => fake()->randomFloat(2, 0, 5),
            'ratings_count' => fake()->numberBetween(0, 2500),
            'metadata' => [],
        ];
    }
}
