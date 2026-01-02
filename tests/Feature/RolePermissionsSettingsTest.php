<?php

use App\Settings\RolePermissions;

test('settings class loads default values correctly', function () {
    $settings = app(RolePermissions::class);

    expect($settings)->toBeInstanceOf(RolePermissions::class);
    expect($settings->admin_pages)->toBeArray();
    expect($settings->pastor_pages)->toBeArray();
    expect($settings->team_member_pages)->toBeArray();
    expect($settings->admin_email)->toBeString();
});

test('admin_pages always contains all pages', function () {
    $settings = app(RolePermissions::class);

    // Admin pages should contain all Filament pages
    expect($settings->admin_pages)->toContain(\App\Filament\Admin\Pages\ImageGenerator::class);
    expect($settings->admin_pages)->toContain(\App\Filament\Admin\Pages\SermonGenerator::class);
    expect($settings->admin_pages)->toContain(\App\Filament\Admin\Pages\MediaLibrary::class);

    // Admin pages should contain all Filament resources
    expect($settings->admin_pages)->toContain(\App\Filament\Admin\Resources\Sermons\SermonResource::class);
    expect($settings->admin_pages)->toContain(\App\Filament\Admin\Resources\Events\EventResource::class);
    expect($settings->admin_pages)->toContain(\App\Filament\Admin\Resources\Albums\AlbumResource::class);
});

test('pastor_pages and team_member_pages are configurable arrays', function () {
    $settings = app(RolePermissions::class);

    // Pastor and team_member pages should be arrays (can be modified)
    expect($settings->pastor_pages)->toBeArray();
    expect($settings->team_member_pages)->toBeArray();

    // They should contain some pages by default (at least the basic resources)
    expect($settings->pastor_pages)->not->toBeEmpty();
    expect($settings->team_member_pages)->not->toBeEmpty();

    // Verify we can update them
    $originalPastorPages = $settings->pastor_pages;
    $settings->pastor_pages = [\App\Filament\Admin\Resources\Sermons\SermonResource::class];
    $settings->save();

    $freshSettings = app(RolePermissions::class);
    expect($freshSettings->pastor_pages)->toBe([\App\Filament\Admin\Resources\Sermons\SermonResource::class]);

    // Restore original values
    $freshSettings->pastor_pages = $originalPastorPages;
    $freshSettings->save();
});

test('admin_email has default value', function () {
    $settings = app(RolePermissions::class);

    expect($settings->admin_email)->toBe('jvw679@gmail.com');
});
