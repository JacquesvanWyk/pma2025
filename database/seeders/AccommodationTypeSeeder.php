<?php

namespace Database\Seeders;

use App\Models\AccommodationType;
use App\Models\MerchandiseItem;
use Illuminate\Database\Seeder;

class AccommodationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Forest Cabin',
                'slug' => 'forest-cabin',
                'description' => 'Comfortable forest cabins nestled among the trees. Perfect for families.',
                'image' => 'camp/cabins.jpeg',
                'base_price' => 1079.00,
                'base_adults' => 2,
                'extra_adult_price' => 348.00,
                'extra_child_price' => 174.00,
                'max_adults' => 6,
                'max_children' => 4,
                'total_units' => 10,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Rondavel (Kitchenette, Toilet & Shower)',
                'slug' => 'rondavel-ensuite',
                'description' => 'Traditional rondavel with kitchenette and en-suite toilet and shower. For 2 adults only.',
                'image' => 'camp/rondawels.jpeg',
                'base_price' => 666.00,
                'base_adults' => 2,
                'extra_adult_price' => null,
                'extra_child_price' => null,
                'max_adults' => 2,
                'max_children' => 0,
                'total_units' => 10,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Rondavel (Kitchenette, Ablution block)',
                'slug' => 'rondavel-ablution',
                'description' => 'Traditional rondavel with kitchenette, shared ablution block.',
                'image' => 'camp/rondawels.jpeg',
                'base_price' => 563.00,
                'base_adults' => 2,
                'extra_adult_price' => 227.00,
                'extra_child_price' => 144.00,
                'max_adults' => 6,
                'max_children' => 4,
                'total_units' => 10,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Camping (No power point)',
                'slug' => 'camping-basic',
                'description' => 'Basic camping site. No power point, no river view. Affordable option for those who want to make it work!',
                'image' => 'camp/camp.jpeg',
                'base_price' => 287.00,
                'base_adults' => 2,
                'extra_adult_price' => 119.00,
                'extra_child_price' => 60.00,
                'max_adults' => 8,
                'max_children' => 6,
                'total_units' => null,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Camping (Riverside, power point)',
                'slug' => 'camping-riverside',
                'description' => 'Premium camping along the river with power point access.',
                'image' => 'camp/camp.jpeg',
                'base_price' => 441.00,
                'base_adults' => 2,
                'extra_adult_price' => 119.00,
                'extra_child_price' => 60.00,
                'max_adults' => 8,
                'max_children' => 6,
                'total_units' => null,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Day Visitor',
                'slug' => 'day-visitor',
                'description' => 'Not staying overnight? Join us for the day. R60 per person, paid at the gate.',
                'image' => 'camp/camp.jpeg',
                'base_price' => 60.00,
                'base_adults' => 1,
                'extra_adult_price' => 60.00,
                'extra_child_price' => null,
                'max_adults' => 100,
                'max_children' => 0,
                'total_units' => null,
                'sort_order' => 6,
                'is_active' => true,
                'is_day_visitor' => true,
            ],
        ];

        foreach ($types as $type) {
            AccommodationType::firstOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }

        // T-shirt placeholder (inactive until price confirmed)
        MerchandiseItem::firstOrCreate(
            ['name' => 'Camp T-Shirt 2026'],
            [
                'name' => 'Camp T-Shirt 2026',
                'price' => 250.00,
                'image' => 'camp/tshirt.jpeg',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'is_active' => false,
            ]
        );
    }
}
