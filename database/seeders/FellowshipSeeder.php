<?php

namespace Database\Seeders;

use App\Models\Fellowship;
use App\Models\User;
use Illuminate\Database\Seeder;

class FellowshipSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('role', 'admin')->first();
        $users = User::factory()->count(5)->create();

        $fellowshipsData = [
            [
                'name' => 'Kayelitsha Community Church',
                'description' => 'A vibrant fellowship reaching the Khayelitsha community with the Gospel through worship, teaching, and community outreach.',
                'phone' => '+27 21 361 2345',
                'email' => 'info@khayelitshacc.org',
                'show_phone' => true,
                'show_email' => true,
                'address' => '45 Lansdowne Road, Khayelitsha',
                'country' => 'South Africa',
                'city' => 'Cape Town',
                'latitude' => -34.0479,
                'longitude' => 18.6730,
                'website' => 'https://khayelitshacc.org',
                'focus_areas' => ['Evangelism', 'Community Development', 'Youth', 'Children'],
                'languages' => ['Xhosa', 'English', 'Afrikaans'],
                'resources' => ['Building', 'Sound System', 'Transport'],
                'tags' => ['Township Ministry', 'Community Church'],
                'member_count' => 150,
                'meeting_time' => 'Sundays at 9:00 AM',
                'privacy_level' => 'public',
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Nairobi New Life Fellowship',
                'description' => 'An English-speaking international fellowship welcoming missionaries and locals for worship and discipleship.',
                'phone' => '+254 20 445 6789',
                'email' => 'welcome@nairobinewlife.org',
                'show_phone' => true,
                'show_email' => true,
                'address' => 'Riverside Drive, Westlands',
                'country' => 'Kenya',
                'city' => 'Nairobi',
                'latitude' => -1.2641,
                'longitude' => 36.8077,
                'website' => 'https://nairobinewlife.org',
                'focus_areas' => ['Discipleship', 'Missions', 'Teaching', 'International Community'],
                'languages' => ['English', 'Swahili'],
                'resources' => ['Meeting Space', 'Library', 'Children\'s Ministry'],
                'tags' => ['International', 'Missionary Fellowship'],
                'member_count' => 80,
                'meeting_time' => 'Sundays at 10:30 AM',
                'privacy_level' => 'public',
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Abuja House Church Network',
                'description' => 'Network of small house churches across Abuja focused on discipleship and evangelism.',
                'phone' => '+234 9 461 2345',
                'email' => 'connect@abujahousechurch.org',
                'show_phone' => false,
                'show_email' => true,
                'address' => 'Maitama District',
                'country' => 'Nigeria',
                'city' => 'Abuja',
                'latitude' => 9.0579,
                'longitude' => 7.4951,
                'focus_areas' => ['Discipleship', 'Evangelism', 'Small Groups'],
                'languages' => ['English', 'Hausa'],
                'resources' => ['Home Venues', 'Training Materials'],
                'tags' => ['House Church', 'Church Planting Movement'],
                'member_count' => 45,
                'meeting_time' => 'Fridays at 7:00 PM',
                'privacy_level' => 'network_only',
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Kampala Grace Chapel',
                'description' => 'A loving community serving the Kampala area through worship, Bible teaching, and medical outreach.',
                'phone' => '+256 41 234 5678',
                'email' => 'info@kampalagrace.org',
                'show_phone' => true,
                'show_email' => true,
                'address' => '23 Nakasero Road',
                'country' => 'Uganda',
                'city' => 'Kampala',
                'latitude' => 0.3163,
                'longitude' => 32.5811,
                'website' => 'https://kampalagrace.org',
                'focus_areas' => ['Worship', 'Teaching', 'Medical', 'Community Outreach'],
                'languages' => ['English', 'Luganda'],
                'resources' => ['Building', 'Medical Clinic', 'School'],
                'tags' => ['Community Church', 'Medical Ministry'],
                'member_count' => 200,
                'meeting_time' => 'Sundays at 9:00 AM and 11:30 AM',
                'privacy_level' => 'public',
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Harare City Light',
                'description' => 'A new church plant seeking to reach young professionals in Harare with the Gospel.',
                'phone' => '+263 24 270 1234',
                'email' => 'hello@hararecitylight.org',
                'show_phone' => true,
                'show_email' => true,
                'address' => 'Central Business District',
                'country' => 'Zimbabwe',
                'city' => 'Harare',
                'latitude' => -17.8292,
                'longitude' => 31.0522,
                'focus_areas' => ['Evangelism', 'Young Adults', 'Discipleship'],
                'languages' => ['English', 'Shona'],
                'resources' => ['Rented Venue'],
                'tags' => ['Church Plant', 'Young Adults'],
                'member_count' => 25,
                'meeting_time' => 'Sundays at 5:00 PM',
                'privacy_level' => 'public',
                'is_approved' => false,
                'is_active' => true,
            ],
        ];

        foreach ($fellowshipsData as $index => $data) {
            Fellowship::create(array_merge($data, [
                'user_id' => $users[$index]->id,
            ]));
        }
    }
}
