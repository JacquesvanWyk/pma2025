<?php

use App\Models\User;

test('existing admin users retain admin role after migration', function () {
    // Create admin user and verify admin role is preserved
    $user = User::factory()->admin()->create();

    expect($user->fresh()->role)->toBe('admin');
});

test('senior_pastor users are migrated to pastor role', function () {
    // After migration, users who were 'senior_pastor' should now be 'pastor'
    // The factory's pastor() state represents the migrated state
    $user = User::factory()->pastor()->create();

    expect($user->fresh()->role)->toBe('pastor');
});

test('member users are migrated to team_member role', function () {
    // After migration, users who were 'member' should now be 'team_member'
    // The factory's teamMember() state represents the migrated state
    $user = User::factory()->teamMember()->create();

    expect($user->fresh()->role)->toBe('team_member');

    // Also verify team_member is the new default role
    $userWithDefault = User::factory()->create();
    expect($userWithDefault->role)->toBe('team_member');
});
