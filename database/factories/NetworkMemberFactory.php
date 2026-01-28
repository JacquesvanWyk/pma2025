<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NetworkMember>
 */
class NetworkMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['individual', 'group']);

        // South African cities with approximate coordinates
        $locations = [
            ['city' => 'Cape Town', 'lat' => -33.9249, 'lng' => 18.4241],
            ['city' => 'Johannesburg', 'lat' => -26.2041, 'lng' => 28.0473],
            ['city' => 'Durban', 'lat' => -29.8587, 'lng' => 31.0218],
            ['city' => 'Pretoria', 'lat' => -25.7479, 'lng' => 28.2293],
            ['city' => 'Port Elizabeth', 'lat' => -33.9608, 'lng' => 25.6022],
            ['city' => 'Bloemfontein', 'lat' => -29.0852, 'lng' => 26.1596],
            ['city' => 'East London', 'lat' => -33.0153, 'lng' => 27.9116],
            ['city' => 'Pietermaritzburg', 'lat' => -29.6019, 'lng' => 30.3794],
            ['city' => 'Kimberley', 'lat' => -28.7282, 'lng' => 24.7499],
            ['city' => 'Polokwane', 'lat' => -23.9045, 'lng' => 29.4689],
        ];

        $location = fake()->randomElement($locations);

        // Add some random offset to coordinates (±0.05 degrees ~ 5km)
        $lat = $location['lat'] + fake()->randomFloat(4, -0.05, 0.05);
        $lng = $location['lng'] + fake()->randomFloat(4, -0.05, 0.05);

        return [
            'user_id' => \App\Models\User::factory(),
            'type' => $type,
            'name' => $type === 'individual' ? fake()->name() : fake()->company().' Fellowship',
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional(0.7)->phoneNumber(),
            'bio' => fake()->paragraphs(2, true),
            'latitude' => $lat,
            'longitude' => $lng,
            'address' => fake()->address().', '.$location['city'].', South Africa',
            'meeting_times' => $type === 'group' ? 'Sundays 10:00 AM' : null,
            'show_email' => fake()->boolean(70),
            'show_phone' => fake()->boolean(30),
            'status' => 'approved',
            'approved_at' => now()->subDays(fake()->numberBetween(1, 30)),
            'approved_by' => 1, // Assume admin user ID is 1
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_at' => null,
            'approved_by' => null,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
        ]);
    }

    public function individual(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'individual',
            'name' => fake()->name(),
            'meeting_times' => null,
        ]);
    }

    public function group(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'group',
            'name' => fake()->company().' Fellowship',
            'meeting_times' => fake()->randomElement([
                'Sundays 10:00 AM',
                'Saturdays 9:00 AM',
                'Fridays 6:00 PM',
                'Wednesdays 7:00 PM',
            ]),
        ]);
    }
}
