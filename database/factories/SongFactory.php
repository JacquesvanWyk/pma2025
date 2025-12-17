<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Song>
 */
class SongFactory extends Factory
{
    public function definition(): array
    {
        return [
            'album_id' => Album::factory(),
            'title' => fake()->words(4, true),
            'slug' => fake()->slug(4),
            'track_number' => fake()->numberBetween(1, 12),
            'duration' => fake()->numberBetween(2, 5).':'.str_pad(fake()->numberBetween(0, 59), 2, '0', STR_PAD_LEFT),
            'wav_file' => null,
            'mp4_video' => null,
            'description' => fake()->sentence(),
            'lyrics' => fake()->paragraphs(3, true),
            'is_published' => true,
            'download_count' => fake()->numberBetween(0, 50),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }

    public function withVideo(): static
    {
        return $this->state(fn (array $attributes) => [
            'mp4_video' => 'albums/videos/'.fake()->uuid().'.mp4',
        ]);
    }
}
