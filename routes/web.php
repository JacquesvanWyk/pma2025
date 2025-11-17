<?php

use App\Http\Controllers\HomeController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // Slide export routes
    Route::get('slides/export/powerpoint', [\App\Http\Controllers\SlideExportController::class, 'exportPowerPoint'])->name('slides.export.powerpoint');
    Route::get('slides/export/pdf', [\App\Http\Controllers\SlideExportController::class, 'exportPdf'])->name('slides.export.pdf');
});

require __DIR__.'/auth.php';
require __DIR__.'/public.php';
