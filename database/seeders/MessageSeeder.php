<?php

namespace Database\Seeders;

use App\Models\Fellowship;
use App\Models\Individual;
use App\Models\Message;
use App\Models\Ministry;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $ministries = Ministry::where('is_approved', true)->get();
        $individuals = Individual::where('is_approved', true)->get();
        $fellowships = Fellowship::where('is_approved', true)->get();

        $messages = [
            [
                'from_user_id' => $users->random()->id,
                'recipient_type' => Ministry::class,
                'recipient_id' => $ministries->first()?->id,
                'subject' => 'Interested in Partnering',
                'message' => 'Hello! I am a medical professional interested in joining one of your mobile medical camps. I have experience in family medicine and public health. Would love to discuss how I can serve.',
                'read_at' => now()->subDays(2),
            ],
            [
                'from_user_id' => $users->random()->id,
                'recipient_type' => Individual::class,
                'recipient_id' => $individuals->first()?->id,
                'subject' => 'Youth Ministry Resources',
                'message' => 'Hi Sarah! I heard about your youth program in Cape Town. We are starting a similar initiative here in Johannesburg. Could we connect to discuss curriculum and best practices?',
                'read_at' => now()->subHours(5),
            ],
            [
                'from_user_id' => $users->random()->id,
                'recipient_type' => Fellowship::class,
                'recipient_id' => $fellowships->first()?->id,
                'subject' => 'Visiting Your Church',
                'message' => 'Greetings from Nairobi! I will be visiting Cape Town next month and would love to attend your Sunday service. What time do you meet, and is there anything I should know before visiting?',
            ],
            [
                'from_user_id' => $users->random()->id,
                'recipient_type' => Ministry::class,
                'recipient_id' => $ministries->skip(1)->first()?->id,
                'subject' => 'Question About Water Projects',
                'message' => 'We are considering implementing a clean water initiative in our area. Your work in Kenya is inspiring! Would you be willing to share lessons learned and perhaps provide guidance as we develop our project?',
            ],
            [
                'from_user_id' => $users->random()->id,
                'recipient_type' => Individual::class,
                'recipient_id' => $individuals->skip(2)->first()?->id,
                'subject' => 'Translation Software Recommendation',
                'message' => 'Hi Grace! I saw your post about Bible translation work. We are starting a translation project in our language group. What translation software do you recommend for beginners?',
                'read_at' => now()->subDays(1),
            ],
            [
                'from_user_id' => $users->random()->id,
                'recipient_type' => Fellowship::class,
                'recipient_id' => $fellowships->skip(1)->first()?->id,
                'subject' => 'Short-Term Mission Opportunity',
                'message' => 'Our team is planning a short-term mission trip to Nairobi in July. We would love to serve alongside your fellowship. We have skills in construction, children\'s ministry, and medical care. Is this something we could explore together?',
            ],
        ];

        foreach ($messages as $messageData) {
            Message::create($messageData);
        }
    }
}
