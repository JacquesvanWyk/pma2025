<?php

use App\Filament\Admin\Pages\RolePermissionsSettings;
use App\Models\User;
use App\Settings\RolePermissions;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

test('settings page accessible only to admin users', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($admin);

    expect(RolePermissionsSettings::canAccess())->toBeTrue();

    Livewire::test(RolePermissionsSettings::class)
        ->assertSuccessful();
});

test('non-admin users receive 403', function () {
    $pastor = User::factory()->pastor()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($pastor);

    expect(RolePermissionsSettings::canAccess())->toBeFalse();

    $teamMember = User::factory()->teamMember()->create([
        'email' => 'virgilcarolus@gmail.com',
    ]);
    $this->actingAs($teamMember);

    expect(RolePermissionsSettings::canAccess())->toBeFalse();
});

test('checkbox matrix displays all pages and resources', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($admin);

    Livewire::test(RolePermissionsSettings::class)
        ->assertSuccessful()
        ->assertFormFieldExists('pastor_pages')
        ->assertFormFieldExists('team_member_pages');
});

test('saving settings persists to database', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($admin);

    $settings = app(RolePermissions::class);
    $originalPastorPages = $settings->pastor_pages;
    $originalTeamMemberPages = $settings->team_member_pages;

    $newPastorPages = [
        \App\Filament\Admin\Resources\Sermons\SermonResource::class,
        \App\Filament\Admin\Resources\Events\EventResource::class,
    ];

    $newTeamMemberPages = [
        \App\Filament\Admin\Resources\Sermons\SermonResource::class,
    ];

    Livewire::test(RolePermissionsSettings::class)
        ->fillForm([
            'pastor_pages' => $newPastorPages,
            'team_member_pages' => $newTeamMemberPages,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    // Verify settings were saved
    $freshSettings = app(RolePermissions::class);
    expect($freshSettings->pastor_pages)->toBe($newPastorPages);
    expect($freshSettings->team_member_pages)->toBe($newTeamMemberPages);

    // Restore original values
    $freshSettings->pastor_pages = $originalPastorPages;
    $freshSettings->team_member_pages = $originalTeamMemberPages;
    $freshSettings->save();
});
