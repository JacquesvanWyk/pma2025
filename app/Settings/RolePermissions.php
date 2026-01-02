<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class RolePermissions extends Settings
{
    /** @var array<string> */
    public array $admin_pages;

    /** @var array<string> */
    public array $pastor_pages;

    /** @var array<string> */
    public array $team_member_pages;

    public string $admin_email;

    public static function group(): string
    {
        return 'role_permissions';
    }
}
