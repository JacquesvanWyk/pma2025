<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\NetworkMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class NetworkMemberSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create a demo user for the network members
        $user = User::firstOrCreate(
            ['email' => 'network@pma.test'],
            [
                'name' => 'Network Demo User',
                'password' => bcrypt('password'),
            ]
        );

        // Get language IDs
        $english = Language::where('code', 'en')->first();
        $afrikaans = Language::where('code', 'af')->first();
        $zulu = Language::where('code', 'zu')->first();
        $xhosa = Language::where('code', 'xh')->first();

        $networkMembers = [
            [
                'name' => 'Johannesburg Fellowship Group',
                'type' => 'group',
                'email' => 'jhb.fellowship@example.com',
                'phone' => '+27 11 123 4567',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
                'bio' => 'A vibrant fellowship group meeting weekly in Johannesburg. We welcome believers from all backgrounds to join us for worship, prayer, and fellowship.',
                'address' => 'Johannesburg CBD, Gauteng',
                'meeting_times' => 'Sundays at 10:00 AM, Wednesdays at 7:00 PM',
                'languages' => [$english, $afrikaans],
                'status' => 'approved',
            ],
            [
                'name' => 'Sarah Botha',
                'type' => 'individual',
                'email' => 'sarah.botha@example.com',
                'phone' => '+27 12 345 6789',
                'latitude' => -25.7479,
                'longitude' => 28.2293,
                'bio' => 'Passionate believer looking to connect with other Christians for fellowship and prayer. I love studying the Word and sharing the gospel.',
                'address' => 'Pretoria, Gauteng',
                'languages' => [$afrikaans, $english],
                'status' => 'approved',
            ],
            [
                'name' => 'Cape Town Believers Network',
                'type' => 'group',
                'email' => 'cape.town.network@example.com',
                'phone' => '+27 21 234 5678',
                'latitude' => -33.9249,
                'longitude' => 18.4241,
                'bio' => 'A network of house churches and fellowship groups in the Cape Town area. We believe in authentic community and living out our faith together.',
                'address' => 'Cape Town, Western Cape',
                'meeting_times' => 'Various small groups throughout the week',
                'languages' => [$english, $xhosa],
                'status' => 'approved',
            ],
            [
                'name' => 'Thabo Molefe',
                'type' => 'individual',
                'email' => 'thabo.m@example.com',
                'latitude' => -29.8587,
                'longitude' => 31.0218,
                'bio' => 'Young believer passionate about reaching the townships with the love of Christ. Looking for fellowship partners.',
                'address' => 'Durban, KwaZulu-Natal',
                'languages' => [$zulu, $english],
                'status' => 'approved',
            ],
            [
                'name' => 'Port Elizabeth Christian Fellowship',
                'type' => 'group',
                'email' => 'pe.fellowship@example.com',
                'phone' => '+27 41 345 6789',
                'latitude' => -33.9608,
                'longitude' => 25.6022,
                'bio' => 'A warm, welcoming fellowship in the heart of Port Elizabeth. We focus on practical Christian living and community outreach.',
                'address' => 'Port Elizabeth, Eastern Cape',
                'meeting_times' => 'Sundays at 9:00 AM, Home groups on Tuesdays',
                'languages' => [$english],
                'status' => 'approved',
            ],
            [
                'name' => 'Maria Viljoen',
                'type' => 'individual',
                'email' => 'maria.v@example.com',
                'latitude' => -33.9249,
                'longitude' => 18.4241,
                'bio' => 'Retired teacher with a heart for mentoring young believers. Available for prayer and Bible study.',
                'address' => 'Cape Town, Western Cape',
                'languages' => [$afrikaans, $english],
                'status' => 'approved',
            ],
            [
                'name' => 'Bloemfontein House Church',
                'type' => 'group',
                'email' => 'bfn.housechurch@example.com',
                'phone' => '+27 51 456 7890',
                'latitude' => -29.0852,
                'longitude' => 26.1596,
                'bio' => 'Intimate house church fellowship in Bloemfontein. We believe in every-member ministry and authentic relationships.',
                'address' => 'Bloemfontein, Free State',
                'meeting_times' => 'Saturdays at 6:00 PM',
                'languages' => [$afrikaans, $english],
                'status' => 'approved',
            ],
            [
                'name' => 'David Nkosi',
                'type' => 'individual',
                'email' => 'david.nkosi@example.com',
                'latitude' => -25.7018,
                'longitude' => 28.1450,
                'bio' => 'Youth leader and worship leader. Looking to connect with other worship leaders and young believers.',
                'address' => 'Midrand, Gauteng',
                'languages' => [$zulu, $english],
                'status' => 'approved',
            ],
        ];

        foreach ($networkMembers as $memberData) {
            $languages = $memberData['languages'];
            unset($memberData['languages']);

            $member = NetworkMember::create(array_merge($memberData, [
                'user_id' => $user->id,
                'show_email' => true,
                'show_phone' => true,
            ]));

            $member->languages()->attach($languages->pluck('id'));
        }
    }
}
