<?php

namespace Database\Seeders;

use App\Models\FeedPost;
use App\Models\Fellowship;
use App\Models\Individual;
use App\Models\Ministry;
use App\Models\PostComment;
use App\Models\PostReaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class FeedPostSeeder extends Seeder
{
    public function run(): void
    {
        $ministries = Ministry::where('is_approved', true)->get();
        $individuals = Individual::where('is_approved', true)->get();
        $fellowships = Fellowship::where('is_approved', true)->get();
        $users = User::all();

        $posts = [
            [
                'author_type' => Ministry::class,
                'author_id' => $ministries->first()?->id,
                'type' => 'update',
                'title' => 'Medical Camp Success!',
                'content' => 'Praise God! Our mobile medical camp in rural Kenya served over 300 people this week. We provided free health screenings, medications, and shared the Gospel with everyone who came. Several people gave their lives to Christ!',
                'is_approved' => true,
                'is_pinned' => true,
            ],
            [
                'author_type' => Individual::class,
                'author_id' => $individuals->skip(1)->first()?->id,
                'type' => 'prayer',
                'title' => 'Prayer for Medical Supplies',
                'content' => 'Dear network family, we are planning another medical outreach next month but are in urgent need of basic medical supplies (bandages, antibiotics, pain medication). Please pray with us that God would provide these resources.',
                'is_approved' => true,
            ],
            [
                'author_type' => Fellowship::class,
                'author_id' => $fellowships->first()?->id,
                'type' => 'testimony',
                'title' => 'Three New Believers Baptized!',
                'content' => 'What an incredible Sunday! We baptized three new believers from our community outreach program. God is moving in Khayelitsha! One testimony: a young man who was involved in gangs is now leading a Bible study group in his neighborhood.',
                'is_approved' => true,
            ],
            [
                'author_type' => Ministry::class,
                'author_id' => $ministries->skip(2)->first()?->id,
                'type' => 'resource',
                'title' => 'Bible Translation Training Materials Available',
                'content' => 'We\'ve developed a comprehensive training manual for local Bible translators working with mother-tongue speakers. These materials are now available free to download from our website. Perfect for those working in oral cultures.',
                'video_url' => 'https://youtube.com/watch?v=example',
                'is_approved' => true,
            ],
            [
                'author_type' => Individual::class,
                'author_id' => $individuals->skip(3)->first()?->id,
                'type' => 'prayer',
                'title' => 'Urgent: Drought Relief Needed',
                'content' => 'The drought in our region is devastating. Many families have lost their crops and livestock. We are organizing emergency food distribution but need partners. Please pray for rain and wisdom as we respond to this crisis.',
                'is_approved' => true,
                'answered_at' => now()->subDays(3),
            ],
            [
                'author_type' => Fellowship::class,
                'author_id' => $fellowships->skip(1)->first()?->id,
                'type' => 'update',
                'title' => 'New Children\'s Ministry Launch',
                'content' => 'Exciting news! We\'re launching a children\'s program every Saturday. We need volunteers who love kids and have a heart to teach them about Jesus. Training will be provided. Comment below or message us if interested!',
                'is_approved' => true,
            ],
            [
                'author_type' => Ministry::class,
                'author_id' => $ministries->skip(1)->first()?->id,
                'type' => 'testimony',
                'title' => 'Clean Water Changes Everything',
                'content' => 'Six months ago, we drilled a well in this village. Today, they hosted a celebration service with over 200 people! The pastor shared that waterborne diseases have dropped dramatically, kids are attending school regularly, and the community church has grown by 40%.',
                'is_approved' => true,
            ],
            [
                'author_type' => Individual::class,
                'author_id' => $individuals->skip(2)->first()?->id,
                'type' => 'prayer',
                'title' => 'Persecution in Northern Region',
                'content' => 'Please pray for our brothers and sisters facing increased pressure in the northern regions. Several church buildings have been destroyed, and believers are meeting in homes. Pray for protection, boldness, and opportunities to share the Gospel.',
                'is_approved' => true,
            ],
        ];

        foreach ($posts as $postData) {
            $post = FeedPost::create($postData);

            $reactionCount = rand(3, min(15, $users->count()));
            $selectedUsers = $users->random($reactionCount);

            foreach ($selectedUsers as $user) {
                PostReaction::create([
                    'feed_post_id' => $post->id,
                    'user_id' => $user->id,
                    'type' => ['like', 'pray', 'amen', 'heart'][rand(0, 3)],
                ]);
            }

            if (rand(0, 1)) {
                $commentCount = rand(1, 5);
                for ($i = 0; $i < $commentCount; $i++) {
                    PostComment::create([
                        'feed_post_id' => $post->id,
                        'user_id' => $users->random()->id,
                        'comment' => [
                            'Praying with you!',
                            'This is such great news! Praise God!',
                            'We would love to partner with you on this.',
                            'Thank you for sharing. Very encouraging!',
                            'Amen! God is faithful.',
                        ][rand(0, 4)],
                    ]);
                }
            }
        }
    }
}
