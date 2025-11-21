<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventRsvp;
use App\Models\Fellowship;
use App\Models\Individual;
use App\Models\Ministry;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $ministries = Ministry::where('is_approved', true)->get();
        $individuals = Individual::where('is_approved', true)->get();
        $fellowships = Fellowship::where('is_approved', true)->get();
        $users = User::all();

        $events = [
            [
                'organizer_type' => Ministry::class,
                'organizer_id' => $ministries->first()?->id,
                'title' => 'Medical Missions Training Conference',
                'description' => 'Three-day intensive training for medical professionals interested in missions work. Topics include cross-cultural healthcare, tropical medicine, resource-limited settings, and spiritual care.',
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addMonths(2)->addDays(3),
                'location' => 'Hope Conference Center',
                'country' => 'South Africa',
                'city' => 'Cape Town',
                'latitude' => -33.9249,
                'longitude' => 18.4241,
                'event_type' => 'Conference',
                'max_attendees' => 50,
                'is_online' => false,
            ],
            [
                'organizer_type' => Fellowship::class,
                'organizer_id' => $fellowships->first()?->id,
                'title' => 'Community Outreach Day',
                'description' => 'Join us for a day of serving our community! We will be distributing food parcels, offering free haircuts, children\'s activities, and sharing the Gospel. All volunteers welcome!',
                'start_date' => now()->addWeeks(2),
                'end_date' => now()->addWeeks(2)->addHours(6),
                'location' => 'Khayelitsha Town Square',
                'country' => 'South Africa',
                'city' => 'Cape Town',
                'latitude' => -34.0479,
                'longitude' => 18.6730,
                'event_type' => 'Outreach',
                'is_online' => false,
            ],
            [
                'organizer_type' => Individual::class,
                'organizer_id' => $individuals->first()?->id,
                'title' => 'Youth Leaders Webinar: Engaging Gen Z',
                'description' => 'Online workshop for youth workers on effectively reaching and discipling Generation Z. Includes discussion on social media, mental health, and cultural engagement.',
                'start_date' => now()->addWeeks(1),
                'end_date' => now()->addWeeks(1)->addHours(2),
                'event_type' => 'Workshop',
                'is_online' => true,
                'online_url' => 'https://zoom.us/j/example123',
            ],
            [
                'organizer_type' => Ministry::class,
                'organizer_id' => $ministries->skip(2)->first()?->id,
                'title' => 'Bible Translation Workshop',
                'description' => 'Hands-on training in Bible translation principles, linguistic analysis, and software tools. Designed for those working with oral languages and unreached people groups.',
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonth()->addDays(5),
                'location' => 'Abuja Training Center',
                'country' => 'Nigeria',
                'city' => 'Abuja',
                'latitude' => 9.0765,
                'longitude' => 7.3986,
                'event_type' => 'Training',
                'max_attendees' => 30,
                'is_online' => false,
            ],
            [
                'organizer_type' => Fellowship::class,
                'organizer_id' => $fellowships->skip(1)->first()?->id,
                'title' => 'Prayer and Worship Night',
                'description' => 'Special evening of intercession and worship. We will be praying for persecuted believers, mission workers, and revival in Africa.',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(5)->addHours(3),
                'location' => 'Nairobi New Life Fellowship',
                'country' => 'Kenya',
                'city' => 'Nairobi',
                'latitude' => -1.2641,
                'longitude' => 36.8077,
                'event_type' => 'Prayer Meeting',
                'is_online' => false,
            ],
            [
                'organizer_type' => Ministry::class,
                'organizer_id' => $ministries->skip(3)->first()?->id,
                'title' => 'Mobile Clinic - Rural Uganda',
                'description' => 'Free medical camp providing health screenings, basic treatment, and health education. Medical professionals needed to volunteer!',
                'start_date' => now()->addWeeks(3),
                'end_date' => now()->addWeeks(3)->addDays(4),
                'location' => 'Gulu District Villages',
                'country' => 'Uganda',
                'city' => 'Gulu',
                'latitude' => 2.7747,
                'longitude' => 32.2989,
                'event_type' => 'Medical Outreach',
                'max_attendees' => 20,
                'is_online' => false,
            ],
        ];

        foreach ($events as $eventData) {
            $event = Event::create($eventData);

            if ($event->max_attendees) {
                $rsvpCount = rand(5, min(15, $event->max_attendees));
                $selectedUsers = $users->random($rsvpCount);

                foreach ($selectedUsers as $user) {
                    EventRsvp::create([
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'status' => ['going', 'maybe', 'not_going'][rand(0, 2)],
                    ]);
                }
            }
        }
    }
}
