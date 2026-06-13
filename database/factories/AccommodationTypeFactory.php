<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AccommodationTypeFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'image' => null,
            'base_price' => fake()->randomFloat(2, 200, 1200),
            'base_adults' => 2,
            'extra_adult_price' => fake()->randomFloat(2, 100, 350),
            'extra_child_price' => fake()->randomFloat(2, 50, 200),
            'max_adults' => 4,
            'max_children' => 4,
            'total_units' => fake()->optional()->numberBetween(2, 20),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function noExtras(): static
    {
        return $this->state(fn () => [
            'extra_adult_price' => null,
            'extra_child_price' => null,
            'max_adults' => 2,
            'max_children' => 0,
        ]);
    }
}
