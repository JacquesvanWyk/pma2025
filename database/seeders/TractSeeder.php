<?php

namespace Database\Seeders;

use App\Models\Tract;
use Illuminate\Database\Seeder;

class TractSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = public_path('storage/tracts/tracts-metadata.json');
        $tractsData = json_decode(file_get_contents($jsonPath), true);

        foreach ($tractsData as $tractData) {
            Tract::create([
                'code' => $tractData['code'],
                'title' => $tractData['title'],
                'title_afrikaans' => $tractData['title_afrikaans'],
                'description' => $tractData['description'],
                'pdf_file' => $tractData['pdf_file'],
                'language' => $tractData['language'],
                'category' => $tractData['category'],
                'slug' => $tractData['slug'],
                'status' => 'published',
                'published_at' => now(),
            ]);
        }
    }
}
