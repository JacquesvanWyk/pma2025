<?php

use App\Models\Album;
use App\Models\Song;

test('music index page can be visited', function () {
    $response = $this->get('/music');

    $response->assertOk();
});

test('music index displays published albums', function () {
    $album = Album::factory()->create([
        'title' => 'Test Album',
        'artist' => 'Test Artist',
        'is_published' => true,
    ]);

    $response = $this->get('/music');

    $response->assertSee('Test Album');
    $response->assertSee('Test Artist');
});

test('music index does not display unpublished albums', function () {
    Album::factory()->create([
        'title' => 'Draft Album',
        'is_published' => false,
    ]);

    $response = $this->get('/music');

    $response->assertDontSee('Draft Album');
});

test('music index displays featured album', function () {
    $featuredAlbum = Album::factory()->create([
        'title' => 'Featured Album',
        'is_published' => true,
        'is_featured' => true,
    ]);

    $response = $this->get('/music');

    $response->assertSee('Featured Album');
});

test('album show page can be visited', function () {
    $album = Album::factory()->create([
        'title' => 'Viewable Album',
        'slug' => 'viewable-album',
        'is_published' => true,
    ]);

    $response = $this->get('/music/viewable-album');

    $response->assertOk();
    $response->assertSee('Viewable Album');
});

test('album show page displays songs', function () {
    $album = Album::factory()->create([
        'title' => 'Album With Songs',
        'slug' => 'album-with-songs',
        'is_published' => true,
    ]);

    Song::factory()->create([
        'album_id' => $album->id,
        'title' => 'Test Song',
        'track_number' => 1,
        'is_published' => true,
    ]);

    $response = $this->get('/music/album-with-songs');

    $response->assertSee('Test Song');
});

test('album show page does not display unpublished songs', function () {
    $album = Album::factory()->create([
        'slug' => 'album-mixed-songs',
        'is_published' => true,
    ]);

    Song::factory()->create([
        'album_id' => $album->id,
        'title' => 'Published Song',
        'is_published' => true,
    ]);

    Song::factory()->create([
        'album_id' => $album->id,
        'title' => 'Hidden Song',
        'is_published' => false,
    ]);

    $response = $this->get('/music/album-mixed-songs');

    $response->assertSee('Published Song');
    $response->assertDontSee('Hidden Song');
});

test('unpublished album returns 404', function () {
    Album::factory()->create([
        'slug' => 'unpublished-album',
        'is_published' => false,
    ]);

    $response = $this->get('/music/unpublished-album');

    $response->assertNotFound();
});

test('album model has correct relationships', function () {
    $album = Album::factory()->create();
    $song = Song::factory()->create(['album_id' => $album->id]);

    expect($album->songs)->toHaveCount(1);
    expect($album->songs->first()->title)->toBe($song->title);
});

test('song model belongs to album', function () {
    $album = Album::factory()->create(['title' => 'Parent Album']);
    $song = Song::factory()->create(['album_id' => $album->id]);

    expect($song->album->title)->toBe('Parent Album');
});

test('album can increment download count', function () {
    $album = Album::factory()->create(['download_count' => 0]);

    $album->incrementDownload();

    expect($album->fresh()->download_count)->toBe(1);
});

test('song can increment download count', function () {
    $song = Song::factory()->create(['download_count' => 0]);

    $song->incrementDownload();

    expect($song->fresh()->download_count)->toBe(1);
});

test('album scopes filter correctly', function () {
    Album::factory()->create(['is_published' => true, 'is_featured' => true]);
    Album::factory()->create(['is_published' => true, 'is_featured' => false]);
    Album::factory()->create(['is_published' => false, 'is_featured' => false]);

    expect(Album::published()->count())->toBe(2);
    expect(Album::featured()->count())->toBe(1);
});
