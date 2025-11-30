<?php

use App\Filament\Admin\Resources\PictureStudies\Pages\CreatePictureStudy;
use App\Filament\Admin\Resources\PictureStudies\Pages\EditPictureStudy;
use App\Filament\Admin\Resources\PictureStudies\Pages\ListPictureStudies;
use App\Models\PictureStudy;
use App\Models\Tag;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('picture study model can be created with factory', function () {
    $pictureStudy = PictureStudy::factory()->create();

    expect($pictureStudy)->toBeInstanceOf(PictureStudy::class)
        ->and($pictureStudy->title)->not->toBeEmpty()
        ->and($pictureStudy->slug)->not->toBeEmpty()
        ->and($pictureStudy->image_path)->not->toBeEmpty();
});

test('picture study can have tags', function () {
    $pictureStudy = PictureStudy::factory()->create();
    $tag = Tag::factory()->create();
    $pictureStudy->tags()->attach($tag);

    expect($pictureStudy->tags)->toHaveCount(1)
        ->and($pictureStudy->tags->first()->id)->toBe($tag->id);
});

test('picture study generates slug automatically', function () {
    $pictureStudy = PictureStudy::create([
        'title' => 'Test Infographic',
        'image_path' => 'test.jpg',
        'status' => 'draft',
    ]);

    expect($pictureStudy->slug)->toBe('test-infographic');
});

test('picture study list page can be rendered', function () {
    Livewire::test(ListPictureStudies::class)
        ->assertSuccessful();
});

test('picture study list page displays records', function () {
    $pictureStudy = PictureStudy::factory()->create(['title' => 'Test Picture Study']);

    Livewire::test(ListPictureStudies::class)
        ->assertCanSeeTableRecords([$pictureStudy]);
});

test('picture study create page can be rendered', function () {
    Livewire::test(CreatePictureStudy::class)
        ->assertSuccessful();
});

test('picture study edit page can be rendered', function () {
    $pictureStudy = PictureStudy::factory()->create();

    Livewire::test(EditPictureStudy::class, ['record' => $pictureStudy->id])
        ->assertSuccessful();
});

test('picture study form loads with correct data', function () {
    $pictureStudy = PictureStudy::factory()->create([
        'title' => 'Original Title',
    ]);

    Livewire::test(EditPictureStudy::class, ['record' => $pictureStudy->id])
        ->assertFormSet([
            'title' => 'Original Title',
        ]);
});

test('published scope returns only published picture studies', function () {
    PictureStudy::factory()->create(['status' => 'published', 'published_at' => now()]);
    PictureStudy::factory()->draft()->create();

    expect(PictureStudy::published()->count())->toBe(1);
});

test('language scope filters by language', function () {
    PictureStudy::factory()->english()->create();
    PictureStudy::factory()->afrikaans()->create();

    expect(PictureStudy::language('en')->count())->toBe(1)
        ->and(PictureStudy::language('af')->count())->toBe(1);
});

test('picture study download count can be incremented', function () {
    $pictureStudy = PictureStudy::factory()->create(['download_count' => 0]);

    $pictureStudy->incrementDownload();

    expect($pictureStudy->fresh()->download_count)->toBe(1);
});
