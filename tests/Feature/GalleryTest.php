<?php

use App\Filament\Admin\Resources\Galleries\Pages\CreateGallery;
use App\Filament\Admin\Resources\Galleries\Pages\EditGallery;
use App\Filament\Admin\Resources\Galleries\Pages\ListGalleries;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\Tag;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('gallery model can be created with factory', function () {
    $gallery = Gallery::factory()->create();

    expect($gallery)->toBeInstanceOf(Gallery::class)
        ->and($gallery->title)->not->toBeEmpty()
        ->and($gallery->slug)->not->toBeEmpty();
});

test('gallery can have images', function () {
    $gallery = Gallery::factory()->create();
    $image = GalleryImage::factory()->create(['gallery_id' => $gallery->id]);

    expect($gallery->images)->toHaveCount(1)
        ->and($gallery->images->first()->id)->toBe($image->id);
});

test('gallery can have tags', function () {
    $gallery = Gallery::factory()->create();
    $tag = Tag::factory()->create();
    $gallery->tags()->attach($tag);

    expect($gallery->tags)->toHaveCount(1)
        ->and($gallery->tags->first()->id)->toBe($tag->id);
});

test('gallery generates slug automatically', function () {
    $gallery = Gallery::create([
        'title' => 'Camp Meeting 2024',
        'status' => 'draft',
    ]);

    expect($gallery->slug)->toBe('camp-meeting-2024');
});

test('gallery list page can be rendered', function () {
    Livewire::test(ListGalleries::class)
        ->assertSuccessful();
});

test('gallery list page displays galleries', function () {
    $gallery = Gallery::factory()->create(['title' => 'Test Gallery']);

    Livewire::test(ListGalleries::class)
        ->assertCanSeeTableRecords([$gallery]);
});

test('gallery create page can be rendered', function () {
    Livewire::test(CreateGallery::class)
        ->assertSuccessful();
});

test('gallery can be created via form', function () {
    Livewire::test(CreateGallery::class)
        ->fillForm([
            'title' => 'New Gallery',
            'description' => 'Gallery description',
            'status' => 'draft',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Gallery::where('title', 'New Gallery')->exists())->toBeTrue();
});

test('gallery edit page can be rendered', function () {
    $gallery = Gallery::factory()->create();

    Livewire::test(EditGallery::class, ['record' => $gallery->id])
        ->assertSuccessful();
});

test('gallery can be updated via form', function () {
    $gallery = Gallery::factory()->create();

    Livewire::test(EditGallery::class, ['record' => $gallery->id])
        ->fillForm([
            'title' => 'Updated Gallery Title',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($gallery->fresh()->title)->toBe('Updated Gallery Title');
});

test('published scope returns only published galleries', function () {
    Gallery::factory()->create(['status' => 'published', 'published_at' => now()]);
    Gallery::factory()->draft()->create();

    expect(Gallery::published()->count())->toBe(1);
});

test('gallery image download count can be incremented', function () {
    $image = GalleryImage::factory()->create(['download_count' => 0]);

    $image->incrementDownload();

    expect($image->fresh()->download_count)->toBe(1);
});
