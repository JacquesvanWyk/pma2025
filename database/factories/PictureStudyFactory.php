<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PictureStudy>
 */
class PictureStudyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'image_path' => 'picture-studies/'.fake()->uuid().'.jpg',
            'language' => fake()->randomElement(['en', 'af']),
            'status' => 'published',
            'published_at' => now()->subDays(fake()->numberBetween(1, 30)),
            'download_count' => fake()->numberBetween(0, 200),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function english(): static
    {
        return $this->state(fn (array $attributes) => [
            'language' => 'en',
        ]);
    }

    public function afrikaans(): static
    {
        return $this->state(fn (array $attributes) => [
            'language' => 'af',
        ]);
    }
}
