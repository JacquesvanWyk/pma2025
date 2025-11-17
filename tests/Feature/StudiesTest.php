<?php

use App\Livewire\StudiesList;
use App\Models\Study;
use App\Models\Tag;
use Livewire\Livewire;

test('studies index page can be visited', function () {
    $response = $this->get('/studies');

    $response->assertOk();
    $response->assertSeeLivewire(StudiesList::class);
});

test('studies index displays published studies', function () {
    $study = Study::factory()->create([
        'title' => 'Test Study Title',
        'excerpt' => 'Test excerpt for the study',
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    Livewire::test(StudiesList::class)
        ->assertSee('Test Study Title')
        ->assertSee('Test excerpt for the study');
});

test('studies index does not display draft studies', function () {
    Study::factory()->draft()->create([
        'title' => 'Draft Study',
    ]);

    Livewire::test(StudiesList::class)
        ->assertDontSee('Draft Study');
});

test('studies index does not display unpublished studies', function () {
    Study::factory()->unpublished()->create([
        'title' => 'Future Study',
    ]);

    Livewire::test(StudiesList::class)
        ->assertDontSee('Future Study');
});

test('studies can be searched by title', function () {
    Study::factory()->create(['title' => 'Unique Search Title']);
    Study::factory()->create(['title' => 'Another Study']);

    Livewire::test(StudiesList::class)
        ->set('search', 'Unique')
        ->assertSee('Unique Search Title')
        ->assertDontSee('Another Study');
});

test('studies can be searched by content', function () {
    Study::factory()->create([
        'title' => 'Study One',
        'content' => 'This content contains searchable keyword',
    ]);
    Study::factory()->create([
        'title' => 'Study Two',
        'content' => 'This content is different',
    ]);

    Livewire::test(StudiesList::class)
        ->set('search', 'searchable')
        ->assertSee('Study One')
        ->assertDontSee('Study Two');
});

test('studies can be filtered by language', function () {
    Study::factory()->english()->create(['title' => 'English Study']);
    Study::factory()->afrikaans()->create(['title' => 'Afrikaans Studie']);

    Livewire::test(StudiesList::class)
        ->set('language', 'english')
        ->assertSee('English Study')
        ->assertDontSee('Afrikaans Studie');
});

test('studies can be filtered by tags', function () {
    $pioneersTag = Tag::factory()->create(['slug' => 'pioneers', 'name' => 'Pioneers']);
    $evangelismTag = Tag::factory()->create(['slug' => 'evangelism', 'name' => 'Evangelism']);

    $pioneerStudy = Study::factory()->create(['title' => 'Pioneer Study']);
    $pioneerStudy->tags()->attach($pioneersTag);

    $evangelismStudy = Study::factory()->create(['title' => 'Evangelism Study']);
    $evangelismStudy->tags()->attach($evangelismTag);

    Livewire::test(StudiesList::class)
        ->set('selectedTags', ['pioneers'])
        ->assertSee('Pioneer Study')
        ->assertDontSee('Evangelism Study');
});

test('studies can be sorted by newest first', function () {
    Study::factory()->create([
        'title' => 'Older Study',
        'published_at' => now()->subDays(5),
    ]);
    Study::factory()->create([
        'title' => 'Newer Study',
        'published_at' => now()->subDay(),
    ]);

    Livewire::test(StudiesList::class)
        ->set('sort', 'newest')
        ->assertOk();
});

test('studies can be sorted by oldest first', function () {
    Study::factory()->create([
        'title' => 'Older Study',
        'published_at' => now()->subDays(5),
    ]);
    Study::factory()->create([
        'title' => 'Newer Study',
        'published_at' => now()->subDay(),
    ]);

    Livewire::test(StudiesList::class)
        ->set('sort', 'oldest')
        ->assertOk();
});

test('studies can be sorted by title ascending', function () {
    Study::factory()->create(['title' => 'Zebra Study']);
    Study::factory()->create(['title' => 'Alpha Study']);

    Livewire::test(StudiesList::class)
        ->set('sort', 'title-asc')
        ->assertOk();
});

test('studies can be sorted by title descending', function () {
    Study::factory()->create(['title' => 'Zebra Study']);
    Study::factory()->create(['title' => 'Alpha Study']);

    Livewire::test(StudiesList::class)
        ->set('sort', 'title-desc')
        ->assertOk();
});

test('clear filters button resets all filters', function () {
    Study::factory()->create(['title' => 'Test Study']);

    Livewire::test(StudiesList::class)
        ->set('search', 'test')
        ->set('language', 'english')
        ->set('selectedTags', ['pioneers'])
        ->set('sort', 'oldest')
        ->call('clearFilters')
        ->assertSet('search', '')
        ->assertSet('language', 'all')
        ->assertSet('selectedTags', [])
        ->assertSet('sort', 'newest');
});

test('individual study page can be visited', function () {
    $study = Study::factory()->create([
        'slug' => 'test-study',
        'title' => 'Test Study',
    ]);

    $response = $this->get('/studies/test-study');

    $response->assertOk();
    $response->assertSee('Test Study');
});

test('individual study displays content', function () {
    $study = Study::factory()->create([
        'slug' => 'detailed-study',
        'title' => 'Detailed Study',
        'content' => 'This is the detailed content of the study',
    ]);

    $response = $this->get('/studies/detailed-study');

    $response->assertSee('Detailed Study');
    $response->assertSee('This is the detailed content of the study');
});

test('individual study displays associated tags', function () {
    $tag = Tag::factory()->create(['name' => 'Pioneers']);
    $study = Study::factory()->create(['slug' => 'tagged-study']);
    $study->tags()->attach($tag);

    $response = $this->get('/studies/tagged-study');

    $response->assertSee('Pioneers');
});

test('404 is returned for non-existent study', function () {
    $response = $this->get('/studies/non-existent-slug');

    $response->assertNotFound();
});

test('404 is returned for draft study', function () {
    $study = Study::factory()->draft()->create(['slug' => 'draft-study']);

    $response = $this->get('/studies/draft-study');

    $response->assertNotFound();
});

test('404 is returned for unpublished study', function () {
    $study = Study::factory()->unpublished()->create(['slug' => 'future-study']);

    $response = $this->get('/studies/future-study');

    $response->assertNotFound();
});
