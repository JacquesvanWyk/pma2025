<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VideoProject>
 */
class VideoProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->sentence(3),
            'type' => 'lyric_video',
            'status' => 'draft',
            'description' => fake()->optional()->paragraph(),
            'background_type' => 'color',
            'background_value' => fake()->hexColor(),
            'text_style' => [
                'font' => 'Arial',
                'font_size' => 48,
                'font_color' => '#FFFFFF',
                'position' => 'center',
                'animation' => 'fade',
                'shadow' => true,
            ],
            'resolution' => '1080p',
            'aspect_ratio' => '16:9',
            'fps' => 30,
            'audio_duration_ms' => fake()->numberBetween(60000, 300000),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'output_path' => 'videos/'.fake()->uuid().'.mp4',
            'processing_started_at' => now()->subMinutes(10),
            'processing_completed_at' => now(),
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processing',
            'processing_started_at' => now()->subMinutes(2),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'error_message' => 'Video generation failed: '.fake()->sentence(),
            'processing_started_at' => now()->subMinutes(5),
            'processing_completed_at' => now(),
        ]);
    }
}
