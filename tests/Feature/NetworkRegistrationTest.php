<?php

use App\Models\Language;
use App\Models\NetworkMember;
use App\Models\User;

describe('network join flow', function () {
    test('guests are redirected to login', function () {
        $this->get(route('network.join'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('message', 'Please login or register to join the believer network.');
    });

    test('authenticated users are redirected to dashboard', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('network.join'))
            ->assertRedirect(route('dashboard'));
    });
});

describe('network registration pages', function () {
    test('guests cannot access individual registration', function () {
        $this->get(route('network.register.individual'))
            ->assertRedirect(route('login'));
    });

    test('guests cannot access fellowship registration', function () {
        $this->get(route('network.register.fellowship'))
            ->assertRedirect(route('login'));
    });

    test('authenticated users can access individual registration', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('network.register.individual'))
            ->assertOk()
            ->assertSee('Register as Individual Believer')
            ->assertSee('Full Name');
    });

    test('authenticated users can access fellowship registration', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('network.register.fellowship'))
            ->assertOk()
            ->assertSee('Register Fellowship Group')
            ->assertSee('Group Name');
    });

    test('users with existing network member are redirected to dashboard from individual registration', function () {
        $user = User::factory()->create();
        NetworkMember::create([
            'user_id' => $user->id,
            'type' => 'individual',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'latitude' => -26.2041,
            'longitude' => 28.0473,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get(route('network.register.individual'))
            ->assertRedirect(route('dashboard'));
    });

    test('users with existing network member are redirected to dashboard from fellowship registration', function () {
        $user = User::factory()->create();
        NetworkMember::create([
            'user_id' => $user->id,
            'type' => 'group',
            'name' => 'Test Fellowship',
            'email' => 'fellowship@example.com',
            'latitude' => -26.2041,
            'longitude' => 28.0473,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get(route('network.register.fellowship'))
            ->assertRedirect(route('dashboard'));
    });
});

describe('individual registration', function () {
    test('can register as individual believer with location', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+27123456789',
                'bio' => 'A believer in South Africa',
                'total_believers' => 1,
                'latitude' => -28.7323601,
                'longitude' => 20.501435,
                'city' => 'Kakamas',
                'province' => 'Northern Cape',
                'country' => 'South Africa',
                'address' => 'Kakamas, Northern Cape',
                'show_email' => true,
                'show_phone' => false,
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('network_members', [
            'user_id' => $user->id,
            'type' => 'individual',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'status' => 'pending',
            'total_believers' => 1,
            'city' => 'Kakamas',
            'province' => 'Northern Cape',
            'country' => 'South Africa',
        ]);
    });

    test('can register with household members', function () {
        $user = User::factory()->create();

        $householdMembers = [
            ['name' => 'Jane Doe'],
            ['name' => 'Jimmy Doe'],
        ];

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
                'total_believers' => 3,
                'household_members' => $householdMembers,
                'show_household_members' => true,
            ])
            ->assertRedirect(route('dashboard'));

        $member = NetworkMember::where('email', 'john@example.com')->first();

        expect($member->total_believers)->toBe(3);
        expect($member->household_members)->toBe($householdMembers);
        expect($member->show_household_members)->toBeTrue();
    });

    test('can register with languages', function () {
        $user = User::factory()->create();
        $languages = Language::factory()->count(2)->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
                'languages' => $languages->pluck('id')->toArray(),
            ])
            ->assertRedirect(route('dashboard'));

        $member = NetworkMember::where('email', 'john@example.com')->first();

        expect($member->languages)->toHaveCount(2);
    });
});

describe('fellowship registration', function () {
    test('can register as fellowship group', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'group',
                'name' => 'Johannesburg Fellowship',
                'email' => 'contact@jbgfellowship.org',
                'phone' => '+27123456789',
                'bio' => 'A fellowship group in Johannesburg',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
                'address' => '123 Main St, Johannesburg',
                'meeting_times' => 'Sundays 10:00 AM',
                'show_email' => true,
                'show_phone' => true,
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('network_members', [
            'user_id' => $user->id,
            'type' => 'group',
            'name' => 'Johannesburg Fellowship',
            'meeting_times' => 'Sundays 10:00 AM',
            'status' => 'pending',
        ]);
    });
});

describe('privacy controls', function () {
    test('email visibility defaults to true', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
            ])
            ->assertRedirect(route('dashboard'));

        $member = NetworkMember::where('email', 'john@example.com')->first();

        expect($member->show_email)->toBeTrue();
    });

    test('phone visibility defaults to false', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
            ])
            ->assertRedirect(route('dashboard'));

        $member = NetworkMember::where('email', 'john@example.com')->first();

        expect($member->show_phone)->toBeFalse();
    });

    test('household members visibility defaults to false', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
                'household_members' => [['name' => 'Jane Doe']],
            ])
            ->assertRedirect(route('dashboard'));

        $member = NetworkMember::where('email', 'john@example.com')->first();

        expect($member->show_household_members)->toBeFalse();
    });
});

describe('dashboard display', function () {
    test('dashboard shows network member profile after registration', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
                'city' => 'Johannesburg',
                'province' => 'Gauteng',
                'country' => 'South Africa',
            ])
            ->assertRedirect(route('dashboard'));

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Your Network Profile')
            ->assertSee('John Doe')
            ->assertSee('Johannesburg')
            ->assertSee('Gauteng')
            ->assertSee('South Africa')
            ->assertSee('Edit Profile')
            ->assertSee('Pending');
    });

    test('dashboard shows edit button for network member', function () {
        $user = User::factory()->create();
        $networkMember = NetworkMember::create([
            'user_id' => $user->id,
            'type' => 'individual',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'latitude' => -26.2041,
            'longitude' => 28.0473,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Edit Profile')
            ->assertSee(route('network.edit', $networkMember));
    });
});

describe('validation', function () {
    test('requires name', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'email' => 'john@example.com',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
            ])
            ->assertSessionHasErrors(['name']);
    });

    test('requires email', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
            ])
            ->assertSessionHasErrors(['email']);
    });

    test('requires valid email', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'invalid-email',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
            ])
            ->assertSessionHasErrors(['email']);
    });

    test('requires latitude', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'longitude' => 28.0473,
            ])
            ->assertSessionHasErrors(['latitude']);
    });

    test('requires longitude', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'latitude' => -26.2041,
            ])
            ->assertSessionHasErrors(['longitude']);
    });

    test('latitude must be between -90 and 90', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'latitude' => 100,
                'longitude' => 28.0473,
            ])
            ->assertSessionHasErrors(['latitude']);
    });

    test('longitude must be between -180 and 180', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'individual',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'latitude' => -26.2041,
                'longitude' => 200,
            ])
            ->assertSessionHasErrors(['longitude']);
    });

    test('type must be individual or group', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('network.store'), [
                'type' => 'invalid',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'latitude' => -26.2041,
                'longitude' => 28.0473,
            ])
            ->assertSessionHasErrors(['type']);
    });
});
