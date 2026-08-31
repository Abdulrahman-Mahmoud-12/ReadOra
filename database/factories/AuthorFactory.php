<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'biography' => fake()->paragraph(),
            'birth_date' => fake()->optional()->dateTimeBetween('-120 years', '-40 years'),
            'death_date' => null,
            'photo_path' => null,
            'external_identifiers' => [],
        ];
    }
}
