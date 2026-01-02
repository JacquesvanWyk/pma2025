<?php

use App\Filament\Concerns\HasRoleAccess;
use App\Models\User;
use App\Settings\RolePermissions;
use Filament\Pages\Page;

/**
 * Test page class that uses the HasRoleAccess trait
 */
class TestPage extends Page
{
    use HasRoleAccess;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document';

    protected string $view = 'filament.admin.pages.test-page';
}

test('admin users can access all pages', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);

    // Even if TestPage is not in settings, admin should have access
    expect(TestPage::canAccess())->toBeTrue();
});

test('pastor users can only access pages in pastor_pages setting', function () {
    $pastor = User::factory()->pastor()->create();
    $this->actingAs($pastor);

    $settings = app(RolePermissions::class);
    $originalPastorPages = $settings->pastor_pages;

    // Add TestPage to pastor_pages
    $settings->pastor_pages = array_merge($originalPastorPages, [TestPage::class]);
    $settings->save();

    expect(TestPage::canAccess())->toBeTrue();

    // Remove TestPage from pastor_pages
    $settings->pastor_pages = $originalPastorPages;
    $settings->save();

    expect(TestPage::canAccess())->toBeFalse();
});

test('team_member users can only access pages in team_member_pages setting', function () {
    $teamMember = User::factory()->teamMember()->create();
    $this->actingAs($teamMember);

    $settings = app(RolePermissions::class);
    $originalTeamMemberPages = $settings->team_member_pages;

    // Add TestPage to team_member_pages
    $settings->team_member_pages = array_merge($originalTeamMemberPages, [TestPage::class]);
    $settings->save();

    expect(TestPage::canAccess())->toBeTrue();

    // Remove TestPage from team_member_pages
    $settings->team_member_pages = $originalTeamMemberPages;
    $settings->save();

    expect(TestPage::canAccess())->toBeFalse();
});

test('unauthorized access returns 403', function () {
    $teamMember = User::factory()->teamMember()->create([
        'email' => 'jvw679@gmail.com', // Needed to access panel
    ]);
    $this->actingAs($teamMember);

    $settings = app(RolePermissions::class);
    $originalTeamMemberPages = $settings->team_member_pages;

    // Ensure TestPage is NOT in team_member_pages
    $settings->team_member_pages = array_filter($originalTeamMemberPages, fn ($page) => $page !== TestPage::class);
    $settings->save();

    // canAccess() should return false for unauthorized users
    expect(TestPage::canAccess())->toBeFalse();

    // Restore original settings
    $settings->team_member_pages = $originalTeamMemberPages;
    $settings->save();
});

test('shouldRegisterNavigation hides pages from navigation for unauthorized users', function () {
    // Test for admin - should see navigation
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    expect(TestPage::shouldRegisterNavigation())->toBeTrue();

    // Test for pastor without access - should not see navigation
    $pastor = User::factory()->pastor()->create();
    $this->actingAs($pastor);

    $settings = app(RolePermissions::class);
    $originalPastorPages = $settings->pastor_pages;

    // Ensure TestPage is NOT in pastor_pages
    $settings->pastor_pages = array_filter($originalPastorPages, fn ($page) => $page !== TestPage::class);
    $settings->save();

    expect(TestPage::shouldRegisterNavigation())->toBeFalse();

    // Restore original settings
    $settings->pastor_pages = $originalPastorPages;
    $settings->save();
});
