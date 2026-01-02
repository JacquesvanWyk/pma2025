# Task Breakdown: Role-Based Page Permissions

## Overview
Total Tasks: 9 Task Groups

This feature implements a configurable role-based permission system for Filament pages using Spatie Laravel Settings. Administrators can control which pages are visible and accessible to each user role (admin, pastor, team_member).

## Task List

### Setup Layer

#### Task Group 1: Package Installation and Configuration
**Dependencies:** None

- [x] 1.0 Complete package installation and configuration
  - [x] 1.1 Install Spatie Laravel Settings packages
    - Run `composer require spatie/laravel-settings`
    - Run `composer require filament/spatie-laravel-settings-plugin:"^4.0"` (updated for Filament v4)
  - [x] 1.2 Publish and run settings migration
    - Run `php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"`
    - **Note:** User must run `php artisan migrate` to create settings table
  - [x] 1.3 Publish settings configuration
    - Run `php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="config"`
  - [x] 1.4 Register settings plugin in AdminPanelProvider
    - **Note:** In Filament v4, the plugin auto-registers via SpatieLaravelSettingsPluginServiceProvider - no manual registration needed
  - [x] 1.5 Verify installation
    - Config file exists at `config/settings.php`
    - Migration file exists at `database/migrations/2022_12_14_083707_create_settings_table.php`
    - **User action required:** Run `php artisan migrate` to create settings table

**Acceptance Criteria:**
- Both packages installed successfully
- Settings table migration created (user must run migrate)
- Config file published and accessible
- Plugin auto-registered via service provider

---

### Database Layer

#### Task Group 2: Role Enum Migration
**Dependencies:** Task Group 1

- [x] 2.0 Complete role enum database changes
  - [x] 2.1 Write 3 focused tests for role migration
    - Test existing `admin` users retain `admin` role after migration
    - Test `senior_pastor` users migrated to `pastor` role
    - Test `member` users migrated to `team_member` role
  - [x] 2.2 Create migration to update role enum
    - Change enum from `['admin', 'senior_pastor', 'pastor', 'member']` to `['admin', 'pastor', 'team_member']`
    - Map `senior_pastor` to `pastor`
    - Map `member` to `team_member`
    - Update default from `member` to `team_member`
  - [x] 2.3 Run migration and verify
    - Run ONLY the 3 tests written in 2.1
    - Verify existing users have correct roles after migration

**Acceptance Criteria:**
- The 3 tests written in 2.1 pass
- Role enum updated to `admin`, `pastor`, `team_member`
- Existing users migrated to appropriate new roles
- Default role is `team_member`

---

### Settings Layer

#### Task Group 3: RolePermissions Settings Class
**Dependencies:** Task Group 2

- [x] 3.0 Complete RolePermissions settings class
  - [x] 3.1 Write 4 focused tests for settings functionality
    - Test settings class loads default values correctly
    - Test admin_pages always contains all pages
    - Test pastor_pages and team_member_pages are configurable arrays
    - Test admin_email has default value
  - [x] 3.2 Create RolePermissions settings class
    - Location: `app/Settings/RolePermissions.php`
    - Extend `Spatie\LaravelSettings\Settings`
    - Properties:
      - `public array $admin_pages` - all page identifiers (always complete)
      - `public array $pastor_pages` - configurable page identifiers
      - `public array $team_member_pages` - configurable page identifiers
      - `public string $admin_email` - default: `jvw679@gmail.com`
  - [x] 3.3 Create settings migration with defaults
    - Location: `database/settings/`
    - Set default values for all properties
    - Include all existing page/resource class names as identifiers
  - [x] 3.4 Register settings class in config
    - Add `RolePermissions::class` to `config/settings.php` settings array
  - [x] 3.5 Run settings migration and verify
    - Run `php artisan migrate`
    - Run ONLY the 4 tests written in 3.1

**Acceptance Criteria:**
- The 4 tests written in 3.1 pass
- RolePermissions class created and functional
- Settings migration creates default values
- Settings retrievable via `app(RolePermissions::class)`

---

### Model Layer

#### Task Group 4: User Model Updates
**Dependencies:** Task Group 2

- [x] 4.0 Complete User model enhancements
  - [x] 4.1 Write 4 focused tests for User model changes
    - Test `isAdmin()` returns true for admin role
    - Test `isAdmin()` returns false for pastor/team_member roles
    - Test new users auto-assigned team_member role when role is null
    - Test existing users with role are not overwritten
  - [x] 4.2 Add `isAdmin()` helper method to User model
    - Location: `app/Models/User.php`
    - Returns `$this->role === 'admin'`
  - [x] 4.3 Implement auto-assign default role
    - Use model `booting` or `creating` event
    - Auto-assign `team_member` when role is null on save
  - [x] 4.4 Update role cast if using enum cast
    - Ensure role field works with new enum values (no enum cast needed - role is string)
  - [x] 4.5 Run User model tests
    - Run ONLY the 4 tests written in 4.1 (5 tests pass)

**Acceptance Criteria:**
- The 4 tests written in 4.1 pass
- `isAdmin()` method works correctly
- New users automatically get `team_member` role
- Existing users retain their roles

---

### Trait Layer

#### Task Group 5: HasRoleAccess Trait
**Dependencies:** Task Groups 3 and 4

- [x] 5.0 Complete HasRoleAccess trait
  - [x] 5.1 Write 5 focused tests for trait functionality
    - Test admin users can access all pages
    - Test pastor users can only access pages in pastor_pages setting
    - Test team_member users can only access pages in team_member_pages setting
    - Test unauthorized access returns 403
    - Test `shouldRegisterNavigation()` hides pages from navigation
  - [x] 5.2 Create HasRoleAccess trait
    - Location: `app/Filament/Concerns/HasRoleAccess.php`
    - Implement `canAccess(): bool` method
    - Implement `shouldRegisterNavigation(): bool` method
    - Check user role against RolePermissions settings
    - Use page/resource fully qualified class name as identifier
  - [x] 5.3 Create helper method for permission checking
    - Method to check if user role has access to specific page identifier
    - Reusable across trait methods (`userHasRoleAccess()`)
  - [x] 5.4 Run trait tests
    - Run ONLY the 5 tests written in 5.1 (all 5 tests pass)

**Acceptance Criteria:**
- The 5 tests written in 5.1 pass
- Trait correctly checks role permissions from settings
- Unauthorized access returns 403
- Navigation hides inaccessible pages

---

### UI Layer

#### Task Group 6: Role Permissions Settings Page
**Dependencies:** Task Groups 3 and 5

- [x] 6.0 Complete Settings page UI
  - [x] 6.1 Write 4 focused tests for Settings page
    - Test page accessible only to admin users
    - Test non-admin users receive 403
    - Test checkbox matrix displays all pages/resources
    - Test saving settings persists to database
  - [x] 6.2 Create RolePermissionsSettings page
    - Location: `app/Filament/Admin/Pages/RolePermissionsSettings.php`
    - Extend `Filament\Pages\SettingsPage`
    - Use `RolePermissions::class` as settings class
  - [x] 6.3 Build checkbox matrix form schema
    - Rows: all pages/resources grouped by navigation group
    - Columns: admin (disabled/always checked), pastor, team_member
    - Use `Forms\Components\CheckboxList` or similar
    - Group by navigation group for organization
  - [x] 6.4 Implement admin-only access control
    - Override `canAccess()` to check `isAdmin()`
    - Set appropriate navigation icon and group
  - [x] 6.5 Add page metadata
    - Title: "Role Permissions"
    - Navigation group: "Settings" or similar
    - Navigation icon: Heroicon shield or similar
  - [x] 6.6 Run Settings page tests
    - Run ONLY the 4 tests written in 6.1 (all 4 tests pass)

**Acceptance Criteria:**
- The 4 tests written in 6.1 pass
- Settings page displays checkbox matrix
- Only admin users can access page
- Settings save correctly to database

---

### Integration Layer

#### Task Group 7: Apply Trait to Pages
**Dependencies:** Task Group 5

- [x] 7.0 Apply HasRoleAccess trait to all Filament pages
  - [x] 7.1 Write 3 focused integration tests
    - Test AI Tools pages hidden from team_member role
    - Test configured pages visible to pastor role
    - Test all pages visible to admin role
  - [x] 7.2 Apply trait to all 13 existing pages (excluding RolePermissionsSettings)
    - `ImageGenerator`
    - `MusicGenerator`
    - `VideoGenerator`
    - `VideoEditor`
    - `SlideGenerator`
    - `SermonGenerator`
    - `StudyGuideGenerator`
    - `GeminiImageGenerator`
    - `MediaLibrary`
    - `ApiUsageStats`
    - `AiGuidelines`
    - `DownloadAnalytics`
    - `WebsiteAnalytics`
  - [x] 7.3 Add `use HasRoleAccess;` to each page class
    - Import trait at top of file
    - Add use statement inside class
  - [x] 7.4 Run integration tests
    - Run ONLY the 3 tests written in 7.1 (all 3 tests pass with 58 assertions)

**Acceptance Criteria:**
- The 3 tests written in 7.1 pass
- All 13 pages have trait applied (RolePermissionsSettings excluded as per requirements)
- Pages properly check role permissions
- Navigation hides inaccessible pages

---

#### Task Group 8: Apply Trait to Resources
**Dependencies:** Task Group 5

- [x] 8.0 Apply HasRoleAccess trait to all Filament resources
  - [x] 8.1 Write 3 focused integration tests
    - Test resources hidden from unauthorized roles
    - Test configured resources visible to authorized roles
    - Test all resources visible to admin role
  - [x] 8.2 Apply trait to all 21 existing resources
    - `AlbumResource`
    - `EmailListResource`
    - `EmailSubscriberResource`
    - `EventResource`
    - `FeedPostResource`
    - `FellowshipResource`
    - `GalleryResource`
    - `IndividualResource`
    - `MessageResource`
    - `MinistryResource`
    - `PictureStudyResource`
    - `PledgeProgressResource`
    - `PrayerRequestResource`
    - `PrayerRoomSessionResource`
    - `SermonResource`
    - `ShortResource`
    - `SlidePresentationResource`
    - `StudyResource`
    - `TagResource`
    - `TractResource`
    - `VideoProjectResource`
  - [x] 8.3 Ensure trait works with Resource class
    - Trait already uses static methods - no adjustments needed
    - Test static `canAccess()` method integration verified
  - [x] 8.4 Run integration tests
    - Run ONLY the 3 tests written in 8.1 (all 3 tests pass with 84 assertions)

**Acceptance Criteria:**
- The 3 tests written in 8.1 pass
- All 21 resources have trait/access control applied
- Resources properly check role permissions
- Navigation hides inaccessible resources

---

### Testing Layer

#### Task Group 9: Test Review and Gap Analysis
**Dependencies:** Task Groups 1-8

- [x] 9.0 Review existing tests and fill critical gaps
  - [x] 9.1 Review tests from Task Groups 2-8
    - Review the 3 tests from Task 2.1 (role migration)
    - Review the 4 tests from Task 3.1 (settings class)
    - Review the 5 tests from Task 4.1 (User model)
    - Review the 5 tests from Task 5.1 (trait)
    - Review the 4 tests from Task 6.1 (Settings page)
    - Review the 3 tests from Task 7.1 (pages integration)
    - Review the 3 tests from Task 8.1 (resources integration)
    - Total existing tests: 27 tests (190 assertions)
  - [x] 9.2 Analyze test coverage gaps for THIS feature only
    - Identified critical edge cases: unauthenticated user, unexpected role, role change mid-session
    - Identified end-to-end workflow gaps: admin configures settings -> pastor sees limited pages
    - Focus on user journey scenarios completed
  - [x] 9.3 Write up to 8 additional strategic tests if necessary
    - Test: unauthenticated user cannot access pages with HasRoleAccess trait
    - Test: user with unexpected role gets no access by default
    - Test: role change mid-session updates page access immediately
    - Test: settings change updates page access for logged-in user
    - Test: end-to-end admin configures settings and pastor sees limited pages
    - Test: new user registration gets team_member role automatically
    - Test: isAdmin helper handles all role values correctly
    - Test: admin always bypasses permission check regardless of settings
    - Created `tests/Feature/RolePermissionsEdgeCasesTest.php` with 8 tests (22 assertions)
  - [x] 9.4 Run all feature-specific tests
    - All 35 tests pass (212 assertions)
    - Test files: RoleMigrationTest, RolePermissionsSettingsTest, UserModelTest, HasRoleAccessTraitTest, RolePermissionsSettingsPageTest, PageRoleAccessIntegrationTest, ResourceRoleAccessIntegrationTest, RolePermissionsEdgeCasesTest

**Acceptance Criteria:**
- [x] All feature-specific tests pass (35 tests total with 212 assertions)
- [x] Critical user workflows covered
- [x] No more than 8 additional tests added (exactly 8 new tests)
- [x] End-to-end scenarios validated

---

## Execution Order

Recommended implementation sequence:

1. **Task Group 1:** Package Installation and Configuration (no dependencies)
2. **Task Group 2:** Role Enum Migration (depends on 1)
3. **Task Group 3:** RolePermissions Settings Class (depends on 2)
4. **Task Group 4:** User Model Updates (depends on 2, can run parallel with 3)
5. **Task Group 5:** HasRoleAccess Trait (depends on 3 and 4)
6. **Task Group 6:** Role Permissions Settings Page UI (depends on 3 and 5)
7. **Task Group 7:** Apply Trait to Pages (depends on 5)
8. **Task Group 8:** Apply Trait to Resources (depends on 5, can run parallel with 7)
9. **Task Group 9:** Test Review and Gap Analysis (depends on all)

## Implementation Notes

### Page Identifiers
Use fully qualified class names as page identifiers:
- Pages: `App\Filament\Admin\Pages\ImageGenerator`
- Resources: `App\Filament\Admin\Resources\Sermons\SermonResource`

### Settings Storage Structure
```php
// Example RolePermissions settings structure
[
    'admin_pages' => [...], // All page/resource class names
    'pastor_pages' => [...], // Subset of pages for pastor role
    'team_member_pages' => [...], // Subset of pages for team_member role
    'admin_email' => 'jvw679@gmail.com',
]
```

### Role Mapping (Migration)
| Old Role | New Role |
|----------|----------|
| admin | admin |
| senior_pastor | pastor |
| pastor | pastor |
| member | team_member |

### Files to Create
- `app/Settings/RolePermissions.php`
- `app/Filament/Concerns/HasRoleAccess.php`
- `app/Filament/Admin/Pages/RolePermissionsSettings.php`
- `database/migrations/xxxx_update_user_roles_enum.php`
- `database/settings/xxxx_create_role_permissions_settings.php`

### Files to Modify
- `app/Models/User.php` - Add `isAdmin()` method and auto-assign role
- `config/settings.php` - Register RolePermissions class
- All 14 Filament pages - Add HasRoleAccess trait
- All 21 Filament resources - Add HasRoleAccess trait or access control
