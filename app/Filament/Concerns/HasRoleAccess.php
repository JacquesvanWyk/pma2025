<?php

namespace App\Filament\Concerns;

use App\Settings\RolePermissions;
use Illuminate\Support\Facades\Auth;

trait HasRoleAccess
{
    /**
     * Determine if the current user can access this page/resource.
     */
    public static function canAccess(): bool
    {
        return static::userHasRoleAccess(static::class);
    }

    /**
     * Determine if this page/resource should appear in navigation.
     */
    public static function shouldRegisterNavigation(): bool
    {
        return static::userHasRoleAccess(static::class);
    }

    /**
     * Check if the authenticated user's role has access to the given page identifier.
     */
    protected static function userHasRoleAccess(string $pageIdentifier): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // Admin users always have access to all pages
        if ($user->isAdmin()) {
            return true;
        }

        $settings = app(RolePermissions::class);
        $role = $user->role;

        // Get the pages array for the user's role
        $allowedPages = match ($role) {
            'pastor' => $settings->pastor_pages,
            'team_member' => $settings->team_member_pages,
            default => [],
        };

        return in_array($pageIdentifier, $allowedPages, true);
    }
}
