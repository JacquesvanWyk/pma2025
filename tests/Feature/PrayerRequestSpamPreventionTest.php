<?php

declare(strict_types=1);

use App\Models\PrayerRequest;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
});

test('legitimate prayer request is accepted', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'John Smith',
        'email' => 'john@example.com',
        'phone' => '123-456-7890',
        'request' => 'Please pray for my family during this difficult time.',
        'is_private' => false,
    ]);

    $response->assertRedirect(route('prayer-room.index'));
    $response->assertSessionHas('success');

    expect(PrayerRequest::count())->toBe(1);
    expect(PrayerRequest::first()->name)->toBe('John Smith');
});

test('honeypot field triggers silent rejection when filled', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'Spam Bot',
        'email' => 'spam@spammer.com',
        'request' => 'This is spam content',
        'website' => 'http://spam-site.com',
    ]);

    $response->assertRedirect(route('prayer-room.index'));
    $response->assertSessionHas('success');

    expect(PrayerRequest::count())->toBe(0);
});

test('disposable email domain is rejected', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'Test User',
        'email' => 'test@checkyourform.xyz',
        'request' => 'Please pray for me.',
    ]);

    $response->assertSessionHasErrors('email');
    expect(PrayerRequest::count())->toBe(0);
});

test('mailinator disposable email is rejected', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'Test User',
        'email' => 'test@mailinator.com',
        'request' => 'Please pray for me.',
    ]);

    $response->assertSessionHasErrors('email');
    expect(PrayerRequest::count())->toBe(0);
});

test('guerrillamail disposable email is rejected', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'Test User',
        'email' => 'test@guerrillamail.com',
        'request' => 'Please pray for me.',
    ]);

    $response->assertSessionHasErrors('email');
    expect(PrayerRequest::count())->toBe(0);
});

test('name with numbers is rejected', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'John123',
        'email' => 'test@example.com',
        'request' => 'Please pray for me.',
    ]);

    $response->assertSessionHasErrors('name');
    expect(PrayerRequest::count())->toBe(0);
});

test('name with special characters except apostrophe and hyphen is rejected', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'John@Smith',
        'email' => 'test@example.com',
        'request' => 'Please pray for me.',
    ]);

    $response->assertSessionHasErrors('name');
    expect(PrayerRequest::count())->toBe(0);
});

test('valid name with apostrophe is accepted', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => "O'Brien",
        'email' => 'test@example.com',
        'request' => 'Please pray for me.',
    ]);

    $response->assertRedirect(route('prayer-room.index'));
    expect(PrayerRequest::count())->toBe(1);
});

test('valid name with hyphen is accepted', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'Mary-Jane',
        'email' => 'test@example.com',
        'request' => 'Please pray for me.',
    ]);

    $response->assertRedirect(route('prayer-room.index'));
    expect(PrayerRequest::count())->toBe(1);
});

test('name with unicode characters is accepted', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'José García',
        'email' => 'test@example.com',
        'request' => 'Please pray for me.',
    ]);

    $response->assertRedirect(route('prayer-room.index'));
    expect(PrayerRequest::count())->toBe(1);
});

test('rate limiting blocks excessive submissions', function () {
    $validData = [
        'name' => 'John Smith',
        'email' => 'john@example.com',
        'request' => 'Please pray for me.',
    ];

    $this->post(route('prayer-room.store'), $validData)->assertRedirect();
    $this->post(route('prayer-room.store'), $validData)->assertRedirect();
    $this->post(route('prayer-room.store'), $validData)->assertRedirect();

    $response = $this->post(route('prayer-room.store'), $validData);
    $response->assertStatus(429);

    expect(PrayerRequest::count())->toBe(3);
});

test('prayer request without email is accepted', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'Anonymous User',
        'request' => 'Please pray for my situation.',
    ]);

    $response->assertRedirect(route('prayer-room.index'));
    expect(PrayerRequest::count())->toBe(1);
    expect(PrayerRequest::first()->email)->toBeNull();
});

test('short name is rejected', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'J',
        'email' => 'test@example.com',
        'request' => 'Please pray for me.',
    ]);

    $response->assertSessionHasErrors('name');
    expect(PrayerRequest::count())->toBe(0);
});

test('empty request is rejected', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'John Smith',
        'email' => 'john@example.com',
        'request' => '',
    ]);

    $response->assertSessionHasErrors('request');
    expect(PrayerRequest::count())->toBe(0);
});

test('short request is rejected', function () {
    $response = $this->post(route('prayer-room.store'), [
        'name' => 'John Smith',
        'email' => 'john@example.com',
        'request' => 'Help',
    ]);

    $response->assertSessionHasErrors('request');
    expect(PrayerRequest::count())->toBe(0);
});
