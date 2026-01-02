<?php

use App\Filament\Admin\Pages\AiGuidelines;
use App\Filament\Admin\Pages\ApiUsageStats;
use App\Filament\Admin\Pages\DownloadAnalytics;
use App\Filament\Admin\Pages\GeminiImageGenerator;
use App\Filament\Admin\Pages\ImageGenerator;
use App\Filament\Admin\Pages\MediaLibrary;
use App\Filament\Admin\Pages\MusicGenerator;
use App\Filament\Admin\Pages\SermonGenerator;
use App\Filament\Admin\Pages\SlideGenerator;
use App\Filament\Admin\Pages\StudyGuideGenerator;
use App\Filament\Admin\Pages\VideoEditor;
use App\Filament\Admin\Pages\VideoGenerator;
use App\Filament\Admin\Pages\WebsiteAnalytics;
use App\Models\User;
use App\Settings\RolePermissions;

test('AI Tools pages are hidden from team_member role when not configured', function () {
    $teamMember = User::factory()->teamMember()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($teamMember);

    $settings = app(RolePermissions::class);
    $originalTeamMemberPages = $settings->team_member_pages;

    // Remove all AI Tools pages from team_member_pages
    $aiToolsPages = [
        ImageGenerator::class,
        GeminiImageGenerator::class,
        MusicGenerator::class,
        VideoGenerator::class,
        VideoEditor::class,
        SlideGenerator::class,
        SermonGenerator::class,
        StudyGuideGenerator::class,
        MediaLibrary::class,
        ApiUsageStats::class,
        AiGuidelines::class,
    ];

    $settings->team_member_pages = array_filter(
        $originalTeamMemberPages,
        fn ($page) => ! in_array($page, $aiToolsPages, true)
    );
    $settings->save();

    // All AI Tools pages should be hidden from team_member
    foreach ($aiToolsPages as $pageClass) {
        expect($pageClass::canAccess())
            ->toBeFalse("Team member should not have access to {$pageClass}");
        expect($pageClass::shouldRegisterNavigation())
            ->toBeFalse("Navigation should be hidden for {$pageClass}");
    }

    // Restore original settings
    $settings->team_member_pages = $originalTeamMemberPages;
    $settings->save();
});

test('configured pages are visible to pastor role', function () {
    $pastor = User::factory()->pastor()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($pastor);

    $settings = app(RolePermissions::class);
    $originalPastorPages = $settings->pastor_pages;

    // Add specific pages to pastor_pages
    $allowedPages = [
        SermonGenerator::class,
        StudyGuideGenerator::class,
        DownloadAnalytics::class,
        WebsiteAnalytics::class,
    ];

    $settings->pastor_pages = array_unique(array_merge($originalPastorPages, $allowedPages));
    $settings->save();

    // Pastor should have access to configured pages
    foreach ($allowedPages as $pageClass) {
        expect($pageClass::canAccess())
            ->toBeTrue("Pastor should have access to {$pageClass}");
        expect($pageClass::shouldRegisterNavigation())
            ->toBeTrue("Navigation should be visible for {$pageClass}");
    }

    // Pages not in pastor_pages should be hidden
    $deniedPages = [
        ImageGenerator::class,
        MusicGenerator::class,
    ];

    $settings->pastor_pages = array_filter(
        $settings->pastor_pages,
        fn ($page) => ! in_array($page, $deniedPages, true)
    );
    $settings->save();

    foreach ($deniedPages as $pageClass) {
        expect($pageClass::canAccess())
            ->toBeFalse("Pastor should not have access to {$pageClass}");
    }

    // Restore original settings
    $settings->pastor_pages = $originalPastorPages;
    $settings->save();
});

test('all pages are visible to admin role', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($admin);

    // All pages should be accessible to admin regardless of settings
    $allPages = [
        ImageGenerator::class,
        GeminiImageGenerator::class,
        MusicGenerator::class,
        VideoGenerator::class,
        VideoEditor::class,
        SlideGenerator::class,
        SermonGenerator::class,
        StudyGuideGenerator::class,
        MediaLibrary::class,
        ApiUsageStats::class,
        AiGuidelines::class,
        DownloadAnalytics::class,
        WebsiteAnalytics::class,
    ];

    foreach ($allPages as $pageClass) {
        expect($pageClass::canAccess())
            ->toBeTrue("Admin should have access to {$pageClass}");
        expect($pageClass::shouldRegisterNavigation())
            ->toBeTrue("Navigation should be visible for admin on {$pageClass}");
    }
});
