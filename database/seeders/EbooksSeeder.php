<?php

namespace Database\Seeders;

use App\Models\Ebook;
use Illuminate\Database\Seeder;

class EbooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metadataPath = storage_path('app/public/ebooks/ebooks-metadata.json');

        if (! file_exists($metadataPath)) {
            $this->command->warn('Ebooks metadata file not found: '.$metadataPath);

            return;
        }

        $metadata = json_decode(file_get_contents($metadataPath), true);

        foreach ($metadata as $ebookData) {
            Ebook::create([
                'title' => $ebookData['title'],
                'author' => $ebookData['author'],
                'edition' => $ebookData['edition'],
                'description' => $ebookData['description'],
                'language' => $ebookData['language'],
                'thumbnail' => $ebookData['thumbnail'],
                'pdf_file' => $ebookData['pdf_file'],
                'download_url' => $ebookData['download_url'],
                'slug' => $ebookData['slug'],
                'is_featured' => false,
                'download_count' => 0,
            ]);
        }

        $this->command->info(count($metadata).' ebooks imported successfully.');
    }
}
