<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // All Filament Pages
        $allPages = [
            \App\Filament\Admin\Pages\AiGuidelines::class,
            \App\Filament\Admin\Pages\ApiUsageStats::class,
            \App\Filament\Admin\Pages\DownloadAnalytics::class,
            \App\Filament\Admin\Pages\GeminiImageGenerator::class,
            \App\Filament\Admin\Pages\ImageGenerator::class,
            \App\Filament\Admin\Pages\MediaLibrary::class,
            \App\Filament\Admin\Pages\MusicGenerator::class,
            \App\Filament\Admin\Pages\SermonGenerator::class,
            \App\Filament\Admin\Pages\SlideGenerator::class,
            \App\Filament\Admin\Pages\StudyGuideGenerator::class,
            \App\Filament\Admin\Pages\VideoEditor::class,
            \App\Filament\Admin\Pages\VideoGenerator::class,
            \App\Filament\Admin\Pages\WebsiteAnalytics::class,
        ];

        // All Filament Resources
        $allResources = [
            \App\Filament\Admin\Resources\Albums\AlbumResource::class,
            \App\Filament\Admin\Resources\EmailLists\EmailListResource::class,
            \App\Filament\Admin\Resources\EmailSubscribers\EmailSubscriberResource::class,
            \App\Filament\Admin\Resources\Events\EventResource::class,
            \App\Filament\Admin\Resources\FeedPosts\FeedPostResource::class,
            \App\Filament\Admin\Resources\Fellowships\FellowshipResource::class,
            \App\Filament\Admin\Resources\Galleries\GalleryResource::class,
            \App\Filament\Admin\Resources\Individuals\IndividualResource::class,
            \App\Filament\Admin\Resources\Messages\MessageResource::class,
            \App\Filament\Admin\Resources\Ministries\MinistryResource::class,
            \App\Filament\Admin\Resources\PictureStudies\PictureStudyResource::class,
            \App\Filament\Admin\Resources\PledgeProgress\PledgeProgressResource::class,
            \App\Filament\Admin\Resources\PrayerRequests\PrayerRequestResource::class,
            \App\Filament\Admin\Resources\PrayerRoomSessions\PrayerRoomSessionResource::class,
            \App\Filament\Admin\Resources\Sermons\SermonResource::class,
            \App\Filament\Admin\Resources\Shorts\ShortResource::class,
            \App\Filament\Admin\Resources\SlidePresentations\SlidePresentationResource::class,
            \App\Filament\Admin\Resources\Studies\StudyResource::class,
            \App\Filament\Admin\Resources\Tags\TagResource::class,
            \App\Filament\Admin\Resources\Tracts\TractResource::class,
            \App\Filament\Admin\Resources\VideoProjects\VideoProjectResource::class,
        ];

        // Admin has access to everything
        $adminPages = array_merge($allPages, $allResources);

        // Pastor has access to most content resources, but not AI tools
        $pastorPages = [
            // Content Resources
            \App\Filament\Admin\Resources\Sermons\SermonResource::class,
            \App\Filament\Admin\Resources\Events\EventResource::class,
            \App\Filament\Admin\Resources\FeedPosts\FeedPostResource::class,
            \App\Filament\Admin\Resources\Messages\MessageResource::class,
            \App\Filament\Admin\Resources\PrayerRequests\PrayerRequestResource::class,
            \App\Filament\Admin\Resources\PrayerRoomSessions\PrayerRoomSessionResource::class,
            \App\Filament\Admin\Resources\Studies\StudyResource::class,
            \App\Filament\Admin\Resources\Tracts\TractResource::class,
            \App\Filament\Admin\Resources\Galleries\GalleryResource::class,
            \App\Filament\Admin\Resources\Albums\AlbumResource::class,
            \App\Filament\Admin\Resources\PictureStudies\PictureStudyResource::class,
            // People Resources
            \App\Filament\Admin\Resources\Individuals\IndividualResource::class,
            \App\Filament\Admin\Resources\Fellowships\FellowshipResource::class,
            \App\Filament\Admin\Resources\Ministries\MinistryResource::class,
            // Email Resources
            \App\Filament\Admin\Resources\EmailLists\EmailListResource::class,
            \App\Filament\Admin\Resources\EmailSubscribers\EmailSubscriberResource::class,
            // Other
            \App\Filament\Admin\Resources\Tags\TagResource::class,
            \App\Filament\Admin\Resources\PledgeProgress\PledgeProgressResource::class,
        ];

        // Team members have access to basic content management
        $teamMemberPages = [
            // Basic Content Resources
            \App\Filament\Admin\Resources\Sermons\SermonResource::class,
            \App\Filament\Admin\Resources\Events\EventResource::class,
            \App\Filament\Admin\Resources\FeedPosts\FeedPostResource::class,
            \App\Filament\Admin\Resources\Galleries\GalleryResource::class,
            \App\Filament\Admin\Resources\Albums\AlbumResource::class,
            // People
            \App\Filament\Admin\Resources\Individuals\IndividualResource::class,
            \App\Filament\Admin\Resources\Fellowships\FellowshipResource::class,
        ];

        $this->migrator->add('role_permissions.admin_pages', $adminPages);
        $this->migrator->add('role_permissions.pastor_pages', $pastorPages);
        $this->migrator->add('role_permissions.team_member_pages', $teamMemberPages);
        $this->migrator->add('role_permissions.admin_email', 'jvw679@gmail.com');
    }
};
