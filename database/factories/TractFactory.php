<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tract>
 */
class TractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'PMAT'.fake()->unique()->numberBetween(100, 999).fake()->randomElement(['E', 'A', 'X', 'P']),
            'title' => fake()->sentence(3),
            'title_afrikaans' => fake()->optional()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'content' => fake()->optional()->paragraphs(3, true),
            'description' => fake()->paragraph(),
            'pdf_file' => fake()->slug().'.pdf',
            'language' => fake()->randomElement(['English', 'Afrikaans', 'Xhosa', 'Portuguese']),
            'category' => fake()->randomElement(['General', 'Doctrine', 'Prophecy', 'Health', 'Evangelism']),
            'status' => 'published',
            'format_config' => null,
            'download_count' => fake()->numberBetween(0, 1000),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
