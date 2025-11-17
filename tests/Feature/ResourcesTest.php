<?php

use App\Models\Ebook;
use App\Models\Tract;

test('resources index page can be visited', function () {
    $response = $this->get('/resources');

    $response->assertOk();
});

test('ebooks page can be visited', function () {
    $response = $this->get('/resources/ebooks');

    $response->assertOk();
});

test('ebooks page displays ebooks from database', function () {
    $ebook = Ebook::factory()->create([
        'title' => 'Test Book Title',
        'author' => 'Test Author',
        'language' => 'English',
        'is_featured' => false,
    ]);

    $response = $this->get('/resources/ebooks');

    $response->assertSee('Test Book Title');
    $response->assertSee('Test Author');
});

test('ebooks page displays featured ebook', function () {
    Ebook::factory()->create([
        'title' => 'Regular Book',
        'is_featured' => false,
    ]);

    $featuredEbook = Ebook::factory()->create([
        'title' => 'Featured Book',
        'is_featured' => true,
    ]);

    $response = $this->get('/resources/ebooks');

    $response->assertSee('Featured Book');
});

test('tracts page can be visited', function () {
    $response = $this->get('/resources/tracts');

    $response->assertOk();
});

test('tracts page displays published tracts', function () {
    $tract = Tract::factory()->create([
        'title' => 'Test Tract Title',
        'code' => 'TEST001E',
        'language' => 'English',
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    $response = $this->get('/resources/tracts');

    $response->assertSee('Test Tract Title');
    $response->assertSee('TEST001E');
});

test('tracts page does not display unpublished tracts', function () {
    Tract::factory()->create([
        'title' => 'Draft Tract',
        'status' => 'draft',
    ]);

    $response = $this->get('/resources/tracts');

    $response->assertDontSee('Draft Tract');
});
