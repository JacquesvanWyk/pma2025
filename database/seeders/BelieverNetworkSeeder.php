<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BelieverNetworkSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MinistrySeeder::class,
            IndividualSeeder::class,
            FellowshipSeeder::class,
            FeedPostSeeder::class,
            MessageSeeder::class,
            EventSeeder::class,
        ]);
    }
}
