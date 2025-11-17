<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            [
                'name' => 'Pioneers',
                'description' => 'Studies about Adventist Pioneer history and beliefs',
            ],
            [
                'name' => 'Bible Scriptures',
                'description' => 'Bible-based studies and scripture analysis',
            ],
            [
                'name' => 'Ellen White',
                'description' => 'Studies related to Ellen White and her writings',
            ],
            [
                'name' => 'Evangelism',
                'description' => 'Evangelistic materials and outreach studies',
            ],
            [
                'name' => 'The Issues',
                'description' => 'Important theological issues and discussions',
            ],
            [
                'name' => 'Godhead',
                'description' => 'Studies about the nature of God, Jesus, and the Holy Spirit',
            ],
            [
                'name' => 'Trinity',
                'description' => 'Studies examining the doctrine of the Trinity',
            ],
            [
                'name' => 'Jesus Christ',
                'description' => 'Studies focused on Jesus Christ and His divine nature',
            ],
            [
                'name' => 'Sabbath',
                'description' => 'Studies about the Sabbath truth',
            ],
            [
                'name' => 'Sanctuary',
                'description' => 'The Sanctuary doctrine and heavenly ministry',
            ],
            [
                'name' => 'Prophecy',
                'description' => 'Biblical prophecy and end-time events',
            ],
            [
                'name' => 'Present Truth',
                'description' => 'Current truth for this time',
            ],
            [
                'name' => 'History',
                'description' => 'Historical studies and Adventist heritage',
            ],
        ];

        foreach ($tags as $tagData) {
            Tag::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($tagData['name'])],
                $tagData
            );
        }
    }
}
