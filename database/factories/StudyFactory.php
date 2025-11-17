<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Study>
 */
class StudyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(5),
            'slug' => fake()->unique()->slug(),
            'content' => fake()->paragraphs(5, true),
            'excerpt' => fake()->sentence(15),
            'language' => fake()->randomElement(['english', 'afrikaans']),
            'status' => 'published',
            'published_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now()->addDays(7),
        ]);
    }

    public function english(): static
    {
        return $this->state(fn (array $attributes) => [
            'language' => 'english',
        ]);
    }

    public function afrikaans(): static
    {
        return $this->state(fn (array $attributes) => [
            'language' => 'afrikaans',
        ]);
    }
}
