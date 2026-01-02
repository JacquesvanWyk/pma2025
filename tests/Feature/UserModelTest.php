<?php

use App\Models\User;

test('isAdmin returns true for admin role', function () {
    $user = User::factory()->admin()->create();

    expect($user->isAdmin())->toBeTrue();
});

test('isAdmin returns false for pastor role', function () {
    $user = User::factory()->pastor()->create();

    expect($user->isAdmin())->toBeFalse();
});

test('isAdmin returns false for team_member role', function () {
    $user = User::factory()->teamMember()->create();

    expect($user->isAdmin())->toBeFalse();
});

test('new users are auto-assigned team_member role when role is null', function () {
    $user = User::factory()->make(['role' => null]);
    $user->save();

    expect($user->fresh()->role)->toBe('team_member');
});

test('existing users with role are not overwritten on save', function () {
    $user = User::factory()->admin()->create();

    $user->name = 'Updated Name';
    $user->save();

    expect($user->fresh()->role)->toBe('admin');
});
