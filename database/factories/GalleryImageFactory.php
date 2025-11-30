<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GalleryImage>
 */
class GalleryImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gallery_id' => Gallery::factory(),
            'image_path' => 'galleries/images/'.fake()->uuid().'.jpg',
            'title' => fake()->sentence(3),
            'caption' => fake()->sentence(),
            'alt_text' => fake()->sentence(5),
            'order_position' => fake()->numberBetween(0, 10),
            'download_count' => fake()->numberBetween(0, 100),
        ];
    }
}
