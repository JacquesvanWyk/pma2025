<?php

use App\Filament\Admin\Resources\Albums\AlbumResource;
use Filament\Facades\Filament;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});
use App\Filament\Admin\Resources\EmailLists\EmailListResource;
use App\Filament\Admin\Resources\EmailSubscribers\EmailSubscriberResource;
use App\Filament\Admin\Resources\Events\EventResource;
use App\Filament\Admin\Resources\FeedPosts\FeedPostResource;
use App\Filament\Admin\Resources\Fellowships\FellowshipResource;
use App\Filament\Admin\Resources\Galleries\GalleryResource;
use App\Filament\Admin\Resources\Individuals\IndividualResource;
use App\Filament\Admin\Resources\Messages\MessageResource;
use App\Filament\Admin\Resources\Ministries\MinistryResource;
use App\Filament\Admin\Resources\PictureStudies\PictureStudyResource;
use App\Filament\Admin\Resources\PledgeProgress\PledgeProgressResource;
use App\Filament\Admin\Resources\PrayerRequests\PrayerRequestResource;
use App\Filament\Admin\Resources\PrayerRoomSessions\PrayerRoomSessionResource;
use App\Filament\Admin\Resources\Sermons\SermonResource;
use App\Filament\Admin\Resources\Shorts\ShortResource;
use App\Filament\Admin\Resources\SlidePresentations\SlidePresentationResource;
use App\Filament\Admin\Resources\Studies\StudyResource;
use App\Filament\Admin\Resources\Tags\TagResource;
use App\Filament\Admin\Resources\Tracts\TractResource;
use App\Filament\Admin\Resources\VideoProjects\VideoProjectResource;
use App\Models\User;
use App\Settings\RolePermissions;

test('resources are hidden from unauthorized roles when not configured', function () {
    $teamMember = User::factory()->teamMember()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($teamMember);

    $settings = app(RolePermissions::class);
    $originalTeamMemberPages = $settings->team_member_pages;

    // Resources NOT in team_member_pages should be hidden
    $restrictedResources = [
        MessageResource::class,
        PrayerRequestResource::class,
        PrayerRoomSessionResource::class,
        StudyResource::class,
        TractResource::class,
        PictureStudyResource::class,
        ShortResource::class,
        SlidePresentationResource::class,
        VideoProjectResource::class,
        MinistryResource::class,
        EmailListResource::class,
        EmailSubscriberResource::class,
        TagResource::class,
        PledgeProgressResource::class,
    ];

    // Remove these resources from team_member_pages to ensure they're not accessible
    $settings->team_member_pages = array_filter(
        $originalTeamMemberPages,
        fn ($page) => ! in_array($page, $restrictedResources, true)
    );
    $settings->save();

    // All restricted resources should be hidden from team_member
    foreach ($restrictedResources as $resourceClass) {
        expect($resourceClass::canAccess())
            ->toBeFalse("Team member should not have access to {$resourceClass}");
        expect($resourceClass::shouldRegisterNavigation())
            ->toBeFalse("Navigation should be hidden for {$resourceClass}");
    }

    // Restore original settings
    $settings->team_member_pages = $originalTeamMemberPages;
    $settings->save();
});

test('configured resources are visible to authorized roles', function () {
    $pastor = User::factory()->pastor()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($pastor);

    $settings = app(RolePermissions::class);
    $originalPastorPages = $settings->pastor_pages;

    // Pastor should have access to these configured resources
    $allowedResources = [
        SermonResource::class,
        EventResource::class,
        GalleryResource::class,
        AlbumResource::class,
        IndividualResource::class,
        FellowshipResource::class,
    ];

    $settings->pastor_pages = array_unique(array_merge($originalPastorPages, $allowedResources));
    $settings->save();

    // Pastor should have access to configured resources
    foreach ($allowedResources as $resourceClass) {
        expect($resourceClass::canAccess())
            ->toBeTrue("Pastor should have access to {$resourceClass}");
        expect($resourceClass::shouldRegisterNavigation())
            ->toBeTrue("Navigation should be visible for {$resourceClass}");
    }

    // Resources not in pastor_pages should be hidden
    $deniedResources = [
        ShortResource::class,
        VideoProjectResource::class,
    ];

    $settings->pastor_pages = array_filter(
        $settings->pastor_pages,
        fn ($page) => ! in_array($page, $deniedResources, true)
    );
    $settings->save();

    foreach ($deniedResources as $resourceClass) {
        expect($resourceClass::canAccess())
            ->toBeFalse("Pastor should not have access to {$resourceClass}");
    }

    // Restore original settings
    $settings->pastor_pages = $originalPastorPages;
    $settings->save();
});

test('all resources are visible to admin role', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($admin);

    // All resources should be accessible to admin regardless of settings
    $allResources = [
        AlbumResource::class,
        EmailListResource::class,
        EmailSubscriberResource::class,
        EventResource::class,
        FeedPostResource::class,
        FellowshipResource::class,
        GalleryResource::class,
        IndividualResource::class,
        MessageResource::class,
        MinistryResource::class,
        PictureStudyResource::class,
        PledgeProgressResource::class,
        PrayerRequestResource::class,
        PrayerRoomSessionResource::class,
        SermonResource::class,
        ShortResource::class,
        SlidePresentationResource::class,
        StudyResource::class,
        TagResource::class,
        TractResource::class,
        VideoProjectResource::class,
    ];

    foreach ($allResources as $resourceClass) {
        expect($resourceClass::canAccess())
            ->toBeTrue("Admin should have access to {$resourceClass}");
        expect($resourceClass::shouldRegisterNavigation())
            ->toBeTrue("Navigation should be visible for admin on {$resourceClass}");
    }
});
