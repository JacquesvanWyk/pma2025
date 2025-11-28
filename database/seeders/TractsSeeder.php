<?php

namespace Database\Seeders;

use App\Models\Tract;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TractsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metadataPath = storage_path('app/public/tracts/tracts-metadata.json');

        if (!file_exists($metadataPath)) {
            $this->command->warn('Tracts metadata file not found: ' . $metadataPath);
            return;
        }

        $metadata = json_decode(file_get_contents($metadataPath), true);

        foreach ($metadata as $tractData) {
            Tract::create([
                'code' => $tractData['code'],
                'title' => $tractData['title'],
                'title_afrikaans' => addslashes($tractData['title_afrikaans']),
                'description' => $tractData['description'],
                'language' => $tractData['language'],
                'pdf_file' => $tractData['pdf_file'],
                'slug' => $tractData['slug'],
                'category' => $tractData['category'],
                'status' => 'published',
                'published_at' => now(),
                'download_count' => 0,
            ]);
        }

        $this->command->info(count($metadata) . ' tracts imported successfully.');
    }
}
