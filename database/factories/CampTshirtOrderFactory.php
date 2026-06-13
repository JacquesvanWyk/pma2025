<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CampTshirtOrderFactory extends Factory
{
    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 3);
        $price = 350.00;
        $donation = fake()->optional(0.3)->randomFloat(2, 0, 200) ?? 0;
        $total = round(($qty * $price) + $donation, 2);

        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'size' => fake()->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            'quantity' => $qty,
            'unit_price' => $price,
            'donation_amount' => $donation,
            'total' => $total,
            'payment_status' => 'pending',
            'transaction_reference' => null,
            'metadata' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn () => [
            'payment_status' => 'paid',
            'transaction_reference' => 'PF-'.fake()->numerify('########'),
        ]);
    }
}
