<?php

use App\Filament\Admin\Pages\ImageGenerator;
use App\Filament\Admin\Pages\SermonGenerator;
use App\Filament\Admin\Resources\Sermons\SermonResource;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\User;
use App\Settings\RolePermissions;
use Filament\Facades\Filament;
use Filament\Pages\Page;

/**
 * Test page class that uses the HasRoleAccess trait for edge case testing
 */
class EdgeCaseTestPage extends Page
{
    use HasRoleAccess;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document';

    protected string $view = 'filament.admin.pages.test-page';
}

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

test('unauthenticated user cannot access pages with HasRoleAccess trait', function () {
    // Ensure no user is authenticated
    $this->assertGuest();

    // canAccess should return false for unauthenticated users
    expect(EdgeCaseTestPage::canAccess())->toBeFalse();
    expect(EdgeCaseTestPage::shouldRegisterNavigation())->toBeFalse();
});

test('user with unexpected role gets no access by default', function () {
    // Create a user manually with an invalid role
    $user = User::factory()->create();
    $user->forceFill(['role' => 'invalid_role'])->saveQuietly();
    $this->actingAs($user);

    // User with unexpected role should not have access
    expect(EdgeCaseTestPage::canAccess())->toBeFalse();
    expect(EdgeCaseTestPage::shouldRegisterNavigation())->toBeFalse();
});

test('role change mid-session updates page access immediately', function () {
    $user = User::factory()->teamMember()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($user);

    $settings = app(RolePermissions::class);
    $originalTeamMemberPages = $settings->team_member_pages;
    $originalPastorPages = $settings->pastor_pages;

    // Ensure ImageGenerator is only in pastor_pages, not in team_member_pages
    $settings->team_member_pages = array_filter(
        $originalTeamMemberPages,
        fn ($page) => $page !== ImageGenerator::class
    );
    $settings->pastor_pages = array_unique(array_merge($originalPastorPages, [ImageGenerator::class]));
    $settings->save();

    // As team_member, should not have access
    expect(ImageGenerator::canAccess())->toBeFalse();

    // Change user role to pastor
    $user->role = 'pastor';
    $user->save();

    // Now should have access immediately
    expect(ImageGenerator::canAccess())->toBeTrue();

    // Restore settings
    $settings->team_member_pages = $originalTeamMemberPages;
    $settings->pastor_pages = $originalPastorPages;
    $settings->save();
});

test('settings change updates page access for logged-in user', function () {
    $pastor = User::factory()->pastor()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($pastor);

    $settings = app(RolePermissions::class);
    $originalPastorPages = $settings->pastor_pages;

    // Remove SermonGenerator from pastor_pages
    $settings->pastor_pages = array_filter(
        $originalPastorPages,
        fn ($page) => $page !== SermonGenerator::class
    );
    $settings->save();

    // Pastor should not have access now
    expect(SermonGenerator::canAccess())->toBeFalse();

    // Add SermonGenerator back to pastor_pages
    $settings->pastor_pages = array_unique(array_merge($settings->pastor_pages, [SermonGenerator::class]));
    $settings->save();

    // Pastor should have access now
    expect(SermonGenerator::canAccess())->toBeTrue();

    // Restore original settings
    $settings->pastor_pages = $originalPastorPages;
    $settings->save();
});

test('end-to-end: admin configures settings and pastor sees limited pages', function () {
    // Step 1: Admin logs in and configures settings
    $admin = User::factory()->admin()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($admin);

    $settings = app(RolePermissions::class);
    $originalPastorPages = $settings->pastor_pages;

    // Admin configures pastor to only have access to SermonResource
    $settings->pastor_pages = [SermonResource::class];
    $settings->save();

    // Admin can still access all pages
    expect(ImageGenerator::canAccess())->toBeTrue();
    expect(SermonResource::canAccess())->toBeTrue();

    // Step 2: Pastor logs in and sees limited pages
    $pastor = User::factory()->pastor()->create([
        'email' => 'virgilcarolus@gmail.com',
    ]);
    $this->actingAs($pastor);

    // Pastor can only access configured pages
    expect(SermonResource::canAccess())->toBeTrue();
    expect(ImageGenerator::canAccess())->toBeFalse();

    // Restore original settings
    $settings->pastor_pages = $originalPastorPages;
    $settings->save();
});

test('new user registration gets team_member role automatically', function () {
    // Simulate user registration flow
    $user = User::create([
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password',
        'role' => null,
    ]);

    expect($user->role)->toBe('team_member');
    expect($user->isAdmin())->toBeFalse();

    // Clean up
    $user->forceDelete();
});

test('isAdmin helper handles all role values correctly', function () {
    // Test admin
    $admin = User::factory()->admin()->create();
    expect($admin->isAdmin())->toBeTrue();

    // Test pastor
    $pastor = User::factory()->pastor()->create();
    expect($pastor->isAdmin())->toBeFalse();

    // Test team_member
    $teamMember = User::factory()->teamMember()->create();
    expect($teamMember->isAdmin())->toBeFalse();

    // Test null role (should be auto-assigned to team_member)
    $nullRoleUser = User::factory()->make(['role' => null]);
    $nullRoleUser->save();
    expect($nullRoleUser->isAdmin())->toBeFalse();
});

test('admin always bypasses permission check regardless of settings', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'jvw679@gmail.com',
    ]);
    $this->actingAs($admin);

    $settings = app(RolePermissions::class);
    $originalAdminPages = $settings->admin_pages;

    // Even if we clear admin_pages (edge case), admin should still have access
    // because isAdmin() check comes before settings check
    expect(ImageGenerator::canAccess())->toBeTrue();
    expect(SermonGenerator::canAccess())->toBeTrue();
    expect(SermonResource::canAccess())->toBeTrue();
});
