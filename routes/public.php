<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\SermonController;
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
Route::get('/network/{networkMember}', [NetworkController::class, 'show'])->name('network.show');
Route::get('/network/join', [NetworkController::class, 'join'])->name('network.join')->middleware('auth');
Route::post('/network/join', [NetworkController::class, 'store'])->name('network.store')->middleware('auth');

// Tract routes
Route::get('/tracts', [TractController::class, 'index'])->name('tracts');
Route::get('/tracts/{slug}', [TractController::class, 'show'])->name('tracts.show');
Route::get('/tracts/{slug}/download/{format?}', [TractController::class, 'download'])->name('tracts.download');

// Resources routes
Route::get('/resources', [ResourcesController::class, 'index'])->name('resources');
Route::get('/resources/tracts', [ResourcesController::class, 'tracts'])->name('resources.tracts');
Route::get('/resources/ebooks', [ResourcesController::class, 'ebooks'])->name('resources.ebooks');

// Other routes
Route::get('/sermons', [SermonController::class, 'index'])->name('sermons');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Donation routes
Route::get('/donate', [DonateController::class, 'index'])->name('donate');
Route::get('/donate/once', [DonateController::class, 'once'])->name('donate.once');
Route::get('/donate/monthly', [DonateController::class, 'monthly'])->name('donate.monthly');
Route::get('/pledge', [DonateController::class, 'pledge'])->name('pledge');

// Placeholder routes
Route::view('/privacy', 'home')->name('privacy');
Route::view('/terms', 'home')->name('terms');
Route::view('/partner', 'home')->name('partner');

Route::post('/newsletter/subscribe', function () {
    return redirect()->route('home')->with('success', 'Thank you for subscribing!');
})->name('newsletter.subscribe');
