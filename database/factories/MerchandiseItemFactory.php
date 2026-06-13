<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MerchandiseItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Camp T-Shirt',
            'price' => 250.00,
            'image' => null,
            'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            'is_active' => false,
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['is_active' => true]);
    }
}
