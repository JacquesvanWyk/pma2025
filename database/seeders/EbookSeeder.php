<?php

namespace Database\Seeders;

use App\Models\Ebook;
use Illuminate\Database\Seeder;

class EbookSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = public_path('storage/ebooks/ebooks-metadata.json');
        $ebooksData = json_decode(file_get_contents($jsonPath), true);

        foreach ($ebooksData as $ebookData) {
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
                'is_featured' => $ebookData['id'] === 1,
            ]);
        }
    }
}
