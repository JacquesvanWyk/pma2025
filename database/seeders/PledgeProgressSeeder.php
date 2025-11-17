<?php

namespace Database\Seeders;

use App\Models\PledgeProgress;
use Illuminate\Database\Seeder;

class PledgeProgressSeeder extends Seeder
{
    public function run(): void
    {
        PledgeProgress::create([
            'current_amount' => 6857.81,
            'month' => 'September',
            'goal_amount' => 35000,
        ]);
    }
}
