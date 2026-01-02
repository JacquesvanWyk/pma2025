# Specification: Role-Based Page Permissions

## Goal
Implement a configurable role-based permission system for Filament pages using Spatie Laravel Settings, allowing administrators to control which pages are visible and accessible to each user role.

## User Stories
- As an admin, I want to configure which Filament pages are visible to each role so that I can control access to sensitive tools like AI generators.
- As a non-admin user, I want to only see pages relevant to my role so that the interface is cleaner and I don't access restricted features.

## Specific Requirements

**Install and Configure Spatie Laravel Settings**
- Install `spatie/laravel-settings` package via Composer
- Install `filament/spatie-laravel-settings-plugin` for Filament integration
- Publish and run settings migration to create `settings` table
- Configure settings in `config/settings.php`

**Update User Role Enum Values**
- Create migration to change role enum from `['admin', 'senior_pastor', 'pastor', 'member']` to `['admin', 'pastor', 'team_member']`
- Current default is `member`, change to `team_member`
- Add `isAdmin()` helper method to User model returning `$this->role === 'admin'`
- Ensure existing users with `senior_pastor` or `member` roles are migrated appropriately

**Create RolePermissions Settings Class**
- Create `App\Settings\RolePermissions` class extending `Spatie\LaravelSettings\Settings`
- Property `admin_pages: array` - list of page identifiers accessible to admin (always all)
- Property `pastor_pages: array` - list of page identifiers accessible to pastor role
- Property `team_member_pages: array` - list of page identifiers accessible to team_member role
- Property `admin_email: string` - configurable admin email (default: jvw679@gmail.com)
- Create corresponding migration for settings defaults

**Create Settings Page in Filament**
- Create `App\Filament\Admin\Pages\RolePermissionsSettings` page
- Use `Filament\Pages\SettingsPage` as base class
- Display checkbox matrix: rows = pages/resources, columns = roles
- Admin column always checked and disabled (admin has access to everything)
- Pastor and team_member columns are editable checkboxes
- Group pages by their navigation group for easier management
- Restrict access to admin role only via `canAccess()` method

**Implement Page Access Control via Trait**
- Create `App\Filament\Concerns\HasRoleAccess` trait
- Implement `canAccess()` method that checks user role against RolePermissions settings
- Trait checks if current page identifier exists in the user's role page list
- Return 403 Forbidden for unauthorized direct URL access
- Apply trait to all existing Filament pages (14 pages)

**Implement Resource Access Control**
- For Resources, implement static `canAccess()` method checking role permissions
- All 21 existing Resources need this implementation
- Consider creating a base Resource class or using trait for consistency

**Auto-Assign Default Role**
- Create observer or use model `booting` method on User model
- Auto-assign `team_member` role when role is null on save
- Ensure this works for new user registration flows

**Page Identifier Strategy**
- Use fully qualified class name as page identifier for uniqueness
- Store in settings as array of class name strings
- Pages: `App\Filament\Admin\Pages\ImageGenerator` etc.
- Resources: `App\Filament\Admin\Resources\Sermons\SermonResource` etc.

## Visual Design
No visual mockups provided. Settings page should follow standard Filament patterns with a checkbox matrix layout.

## Existing Code to Leverage

**User Model Role Field (`app/Models/User.php`)**
- Role field already exists with enum constraint in database
- `canAccessPanel()` method exists with email whitelist pattern
- Add `isAdmin()` helper method following same pattern

**Filament Admin Pages Structure (`app/Filament/Admin/Pages/`)**
- 14 existing pages including AI Tools (ImageGenerator, MusicGenerator, etc.)
- Pages already use `$navigationGroup` property for grouping
- AI Tools group contains: ImageGenerator, MusicGenerator, VideoGenerator, VideoEditor, SlideGenerator, SermonGenerator, StudyGuideGenerator, GeminiImageGenerator, MediaLibrary, ApiUsageStats, AiGuidelines

**Filament Admin Resources Structure (`app/Filament/Admin/Resources/`)**
- 21 existing resources organized in subdirectories (Sermons, Albums, Events, etc.)
- Resources extend `Filament\Resources\Resource`
- Follow existing pattern for `canAccess()` implementation

**AdminPanelProvider (`app/Providers/Filament/AdminPanelProvider.php`)**
- Autodiscovers Resources and Pages from `app/Filament/Admin/` directories
- May need to register settings page plugin here

**Users Migration (`database/migrations/0001_01_01_000000_create_users_table.php`)**
- Current enum: `['admin', 'senior_pastor', 'pastor', 'member']` with default `member`
- Reference for creating role update migration

## Out of Scope
- Per-user custom permissions (only per-role)
- Separate super_admin concept
- Changes to panel access logic (email whitelist remains unchanged)
- Custom 403 error pages
- Permission middleware implementation
- Policy-based authorization
- Navigation group visibility control (only page-level)
- API endpoint permissions
- Widget visibility control
- Custom permission names (using page class names)
