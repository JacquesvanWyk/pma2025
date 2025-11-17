<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ebook>
 */
class EbookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'edition' => fake()->optional()->randomElement(['First Edition', 'Second Edition', 'Third Edition']),
            'description' => fake()->paragraph(),
            'language' => fake()->randomElement(['English', 'Afrikaans']),
            'thumbnail' => fake()->optional()->imageUrl(300, 400, 'books'),
            'pdf_file' => fake()->slug().'.pdf',
            'download_url' => fake()->optional()->url(),
            'slug' => fake()->unique()->slug(),
            'is_featured' => fake()->boolean(10),
            'download_count' => fake()->numberBetween(0, 1000),
        ];
    }
}
