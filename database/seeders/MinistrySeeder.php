<?php

namespace Database\Seeders;

use App\Models\Ministry;
use App\Models\User;
use Illuminate\Database\Seeder;

class MinistrySeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('role', 'admin')->first();

        $ministries = [
            [
                'name' => 'Hope Africa Mission',
                'description' => 'Bringing hope and transformation to communities across Africa through education, healthcare, and spiritual development.',
                'country' => 'South Africa',
                'city' => 'Cape Town',
                'latitude' => -33.9249,
                'longitude' => 18.4241,
                'website' => 'https://hopeafrica.org',
                'youtube' => 'https://youtube.com/@hopeafrica',
                'facebook' => 'https://facebook.com/hopeafrica',
                'focus_areas' => ['Evangelism', 'Medical', 'Children', 'Education'],
                'languages' => ['English', 'Afrikaans', 'Xhosa'],
                'tags' => ['Africa', 'Community Development', 'Healthcare'],
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Living Waters Kenya',
                'description' => 'Providing clean water, agricultural training, and discipleship to rural communities in Kenya.',
                'country' => 'Kenya',
                'city' => 'Nairobi',
                'latitude' => -1.2921,
                'longitude' => 36.8219,
                'website' => 'https://livingwaterskenya.org',
                'focus_areas' => ['Agriculture', 'Water Projects', 'Evangelism', 'Teaching'],
                'languages' => ['English', 'Swahili', 'Kikuyu'],
                'tags' => ['Water', 'Agriculture', 'Discipleship'],
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Harvest Fields Nigeria',
                'description' => 'Church planting and Bible translation ministry reaching unreached people groups in Northern Nigeria.',
                'country' => 'Nigeria',
                'city' => 'Abuja',
                'latitude' => 9.0765,
                'longitude' => 7.3986,
                'website' => 'https://harvestfieldsnigeria.org',
                'instagram' => 'https://instagram.com/harvestfieldsnigeria',
                'twitter' => 'https://twitter.com/harvestfieldsnigeria',
                'focus_areas' => ['Bible Translation', 'Church Planting', 'Evangelism'],
                'languages' => ['English', 'Hausa', 'Yoruba', 'Igbo'],
                'tags' => ['Bible Translation', 'Church Planting', 'Unreached Peoples'],
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Grace Medical Missions',
                'description' => 'Mobile medical clinics providing healthcare and sharing the Gospel in remote areas of East Africa.',
                'country' => 'Uganda',
                'city' => 'Kampala',
                'latitude' => 0.3476,
                'longitude' => 32.5825,
                'website' => 'https://gracemedical.org',
                'facebook' => 'https://facebook.com/gracemedical',
                'youtube' => 'https://youtube.com/@gracemedical',
                'focus_areas' => ['Medical', 'Evangelism', 'Community Development'],
                'languages' => ['English', 'Luganda', 'Swahili'],
                'tags' => ['Healthcare', 'Mobile Clinics', 'Medical Missions'],
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Youth for Christ Zimbabwe',
                'description' => 'Empowering young people through leadership training, sports ministry, and evangelism.',
                'country' => 'Zimbabwe',
                'city' => 'Harare',
                'latitude' => -17.8252,
                'longitude' => 31.0335,
                'website' => 'https://yfczimbabwe.org',
                'instagram' => 'https://instagram.com/yfczimbabwe',
                'focus_areas' => ['Youth', 'Leadership', 'Evangelism', 'Sports'],
                'languages' => ['English', 'Shona', 'Ndebele'],
                'tags' => ['Youth Ministry', 'Leadership Training', 'Sports'],
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'New Hope Community',
                'description' => 'A new ministry seeking approval to work with orphans and vulnerable children in Malawi.',
                'country' => 'Malawi',
                'city' => 'Lilongwe',
                'latitude' => -13.9626,
                'longitude' => 33.7741,
                'focus_areas' => ['Children', 'Education', 'Community Development'],
                'languages' => ['English', 'Chichewa'],
                'tags' => ['Orphans', 'Children', 'Education'],
                'is_approved' => false,
                'is_active' => true,
            ],
        ];

        foreach ($ministries as $ministry) {
            Ministry::create($ministry);
        }
    }
}
