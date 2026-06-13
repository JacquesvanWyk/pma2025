<?php

namespace Database\Factories;

use App\Models\AccommodationType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampBookingFactory extends Factory
{
    public function definition(): array
    {
        $adults = fake()->numberBetween(1, 4);
        $children = fake()->numberBetween(0, 2);
        $nights = fake()->numberBetween(1, 5);

        return [
            'accommodation_type_id' => AccommodationType::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'adults' => $adults,
            'children' => $children,
            'nights' => $nights,
            'arrival_date' => null,
            'departure_date' => null,
            'estimated_total' => fake()->randomFloat(2, 300, 5000),
            'deposit_amount' => fake()->randomFloat(2, 150, 2500),
            'deposit_paid' => false,
            'deposit_paid_at' => null,
            'proof_of_payment' => null,
            'status' => 'pending',
            'notes' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn () => ['status' => 'confirmed']);
    }

    public function depositPaid(): static
    {
        return $this->state(fn () => [
            'deposit_paid' => true,
            'deposit_paid_at' => now(),
        ]);
    }
}
