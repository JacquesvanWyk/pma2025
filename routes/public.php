<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PictureStudyController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\SermonController;
use App\Http\Controllers\ShortController;
use App\Http\Controllers\StudiesController;
use App\Http\Controllers\TractController;
use Illuminate\Support\Facades\Route;

// About routes
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/about/principles', [AboutController::class, 'principles'])->name('about.principles');
Route::get('/about/support', [AboutController::class, 'support'])->name('about.support');

// Studies routes
Route::get('/studies', [StudiesController::class, 'index'])->name('studies');
Route::get('/studies/{slug}', [StudiesController::class, 'show'])->name('studies.show');

// Network routes
Route::get('/network', [NetworkController::class, 'index'])->name('network.index');
Route::get('/network/join', [NetworkController::class, 'join'])->name('network.join');
Route::get('/network/register/individual', [NetworkController::class, 'registerIndividual'])->name('network.register.individual')->middleware('auth');
Route::get('/network/register/fellowship', [NetworkController::class, 'registerFellowship'])->name('network.register.fellowship')->middleware('auth');
Route::post('/network/register', [NetworkController::class, 'store'])->name('network.store')->middleware('auth');
Route::get('/network/{networkMember}/edit', [NetworkController::class, 'edit'])->name('network.edit')->middleware('auth');
Route::put('/network/{networkMember}', [NetworkController::class, 'update'])->name('network.update')->middleware('auth');
Route::get('/network/{networkMember}', [NetworkController::class, 'show'])->name('network.show');

// Ministry routes
Route::get('/network/register/ministry', [NetworkController::class, 'createMinistry'])->name('network.register.ministry')->middleware('auth');
Route::post('/network/register/ministry', [NetworkController::class, 'storeMinistry'])->name('network.store.ministry')->middleware('auth');
Route::get('/network/ministry/{ministry}/edit', [NetworkController::class, 'editMinistry'])->name('network.ministry.edit')->middleware('auth');
Route::put('/network/ministry/{ministry}', [NetworkController::class, 'updateMinistry'])->name('network.ministry.update')->middleware('auth');

// Tract routes
Route::get('/tracts', [TractController::class, 'index'])->name('tracts');
Route::get('/tracts/{slug}', [TractController::class, 'show'])->name('tracts.show');
Route::get('/tracts/{slug}/download/{format?}', [TractController::class, 'download'])->name('tracts.download');

// Ebook routes
Route::get('/ebooks/{slug}', [EbookController::class, 'show'])->name('ebooks.show');
Route::get('/ebooks/{slug}/download', [EbookController::class, 'download'])->name('ebooks.download');

// Resources routes
Route::get('/resources', [ResourcesController::class, 'index'])->name('resources');
Route::get('/resources/tracts', [ResourcesController::class, 'tracts'])->name('resources.tracts');
Route::get('/resources/ebooks', [ResourcesController::class, 'ebooks'])->name('resources.ebooks');
Route::get('/resources/notes', [NoteController::class, 'index'])->name('resources.notes');
Route::get('/resources/picture-studies', [PictureStudyController::class, 'index'])->name('resources.picture-studies');

// Gallery routes
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/gallery/{slug}', [GalleryController::class, 'show'])->name('gallery.show');

// Music routes
Route::get('/music', [MusicController::class, 'index'])->name('music.index');
Route::get('/music/{slug}', [MusicController::class, 'show'])->name('music.show');
Route::get('/music/{album}/songs/{song}/download/audio/{format?}', [MusicController::class, 'downloadSong'])->name('music.download.song')->where('format', 'mp3|wav');
Route::get('/music/{album}/songs/{song}/download/video', [MusicController::class, 'downloadSongVideo'])->name('music.download.song.video');
Route::get('/music/{album}/songs/{song}/download/lyrics', [MusicController::class, 'downloadSongLyrics'])->name('music.download.song.lyrics');
Route::get('/music/{album}/songs/{song}/download/bundle', [MusicController::class, 'downloadSongBundle'])->name('music.download.song.bundle');
Route::get('/music/{album}/download/{type}', [MusicController::class, 'downloadAlbum'])->name('music.download.album')->where('type', 'mp3|wav|audio|video|full');

// Other routes
Route::get('/sermons', [SermonController::class, 'index'])->name('sermons');
Route::get('/shorts', [ShortController::class, 'index'])->name('shorts');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Donation routes
Route::get('/donate', [DonateController::class, 'index'])->name('donate');
Route::get('/donate/once', [DonateController::class, 'once'])->name('donate.once');
Route::get('/donate/monthly', [DonateController::class, 'monthly'])->name('donate.monthly');
Route::get('/pledge', [DonateController::class, 'pledge'])->name('pledge');
Route::post('/donate/notify', [DonateController::class, 'notify'])->name('donate.notify');
Route::post('/paypal_webhook', [DonateController::class, 'paypalWebhook'])->name('paypal.webhook');
Route::post('/paypal/create-plan', [DonateController::class, 'createPayPalPlan'])->name('paypal.create-plan');
Route::get('/api/exchange-rate', [DonateController::class, 'getExchangeRate'])->name('api.exchange-rate');

// Legal routes
Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/terms', 'legal.terms')->name('terms');
Route::view('/partner', 'home')->name('partner');

Route::post('/newsletter/subscribe', function () {
    return redirect()->route('home')->with('success', 'Thank you for subscribing!');
})->name('newsletter.subscribe');
