# Spec Requirements: Hide AI Tools from Non-Admin Users

## Initial Description
Hide ALL AI tool pages in the Filament admin panel from non-admin users. Only users with the `admin` role should see and access these pages.

## Requirements Discussion

### First Round Questions

**Q1:** I assume you want to hide ALL pages in the AI Tools navigation group. This includes: Image Generator, Music Generator, Video Generator, Lyric Video Generator, Video Editor, Slide Generator, Sermon Generator, Study Guide Generator, Gemini Image Generator, Media Library, and API Usage Stats. Is that correct, or should only specific pages be hidden?
**Answer:** Hide ALL AI tool pages from non-admins. All 10+ pages should be admin-only.

**Q2:** I'm assuming we should use Filament's built-in `canAccess()` and `shouldRegisterNavigation()` methods on each page to check if the user has the `admin` role. Should we also consider a more centralized approach (like a Policy or middleware)?
**Answer:** Use Filament's built-in `canAccess()` method on each page. Keep it simple, no middleware or policies needed.

**Q3:** For non-admin users who try to directly access an AI tool URL (e.g., `/admin/image-generator`), I assume they should see a 403 Forbidden error. Is that acceptable, or would you prefer a redirect to the dashboard with a notification?
**Answer:** 403 Forbidden error is fine. Simple and clear.

**Q4:** Currently, panel access itself is controlled by a hardcoded email whitelist in the User model's `canAccessPanel()` method. Should this feature work alongside that existing restriction, or should we adjust the panel access logic as well?
**Answer:** Keep existing email whitelist for panel access. This feature works alongside it - whitelist controls who can access admin panel at all, role controls what they see inside.

**Q5:** I notice the roles are stored as an enum: `admin`, `senior_pastor`, `pastor`, `member`. Should we add an `isAdmin()` helper method to the User model for cleaner code reuse, or just check `$user->role === 'admin'` directly?
**Answer:** Yes, add `isAdmin()` helper to User model. Cleaner code.

**Q6:** Is there anything that should explicitly be EXCLUDED from this feature? For example, should API Usage Stats remain visible to all users, or should all AI-related pages be hidden uniformly?
**Answer:** No exclusions. Hide ALL AI-related pages uniformly from non-admins.

### Follow-up Questions

**Follow-up 1:** Role Naming Clarification - Should there be a distinction between "super_admin" (the one configurable email with full access) and "admin" (a role that can be assigned to multiple users)?
**Answer:** Just have "admin" role - no separate super_admin concept. The configurable admin email (default jvw679@gmail.com) will have admin role with full access.

**Follow-up 2:** Should the visibility settings be per-role (all users with same role see same pages) or per-user (each individual user can have custom page access)?
**Answer:** Per-role (all users with same role see same pages) - simpler approach.

**Follow-up 3:** Since these are admin team members (not church congregation members), would a different name than "member" be clearer?
**Answer:** Rename "member" to "team_member". Final roles: `admin`, `pastor`, `team_member`

**Follow-up 4:** What should happen if a user can access the Filament panel but has no role assigned?
**Answer:** Auto-assign "team_member" role to users without a role.

**Follow-up 5:** Should AI tool pages have additional restrictions beyond role-based visibility, or be treated the same as all other pages?
**Answer:** Treat same as other pages - all pages configurable per role through settings.

### Existing Code to Reference

No similar existing features identified for reference.

## Visual Assets

### Files Provided:
No visual assets provided.

### Visual Insights:
Not applicable - this is primarily backend logic with a settings UI.

## Requirements Summary

### Functional Requirements
- Install Spatie Laravel Settings plugin for Filament
- Create a Role Permissions Settings page in Filament
- Settings page accessible only to admin role
- Configurable admin email in settings/environment (default: jvw679@gmail.com)
- Per-role visibility configuration for ALL Filament pages
- Three roles: `admin` (full access), `pastor`, `team_member`
- Users without a role auto-assigned `team_member` role
- Admin role always has access to all pages
- Non-admin users restricted based on settings configuration

### Role Definitions
| Role | Description | Default Access |
|------|-------------|----------------|
| `admin` | Full access to all pages and settings | All pages |
| `pastor` | Leadership role with configurable access | Configurable |
| `team_member` | Default role for new/unassigned users | Configurable (limited) |

### Settings Page Requirements
- List all Filament pages/resources
- Checkbox matrix: pages vs roles
- Admin role checkboxes always checked and disabled
- Save configuration to database via Spatie Settings
- Only accessible to admin users

### Filament Pages to Include in Settings
All existing Filament pages should be configurable, including but not limited to:
- ImageGenerator
- MusicGenerator
- VideoGenerator
- LyricVideoGenerator
- VideoEditor
- SlideGenerator
- SermonGenerator
- StudyGuideGenerator
- GeminiImageGenerator
- MediaLibrary
- ApiUsageStats
- Stats (dashboard - possibly always visible but still configurable)
- All other existing pages and resources

### Reusability Opportunities
- The `isAdmin()` helper on User model can be reused for future role-based access control
- The settings pattern can be extended for other configuration needs
- Per-role visibility can be applied to navigation groups as well

### Scope Boundaries
**In Scope:**
- Install and configure Spatie Settings plugin
- Create RolePermissions settings class
- Create Role Permissions settings page in Filament
- Add `isAdmin()` method to User model
- Update role enum to: `admin`, `pastor`, `team_member`
- Implement `canAccess()` on all Filament pages using settings
- Auto-assign `team_member` role to users without a role
- Return 403 for unauthorized direct URL access

**Out of Scope:**
- Changes to panel access logic (email whitelist remains unchanged)
- Per-user custom permissions (using per-role only)
- Separate super_admin concept
- Custom error pages for 403

### Technical Considerations
- Use Spatie Laravel Settings plugin (filament-spatie-settings)
- Store role-to-page mappings in settings table
- Use Filament's built-in `canAccess()` method which handles both navigation visibility and access control
- Check user role against settings configuration within each page's `canAccess()` method
- Existing email whitelist in `canAccessPanel()` remains unchanged
- Role field enum values updated to: `admin`, `pastor`, `team_member`
- Consider using a trait or base class for consistent `canAccess()` implementation across pages
