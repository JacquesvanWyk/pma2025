<?php

use App\Http\Controllers\HomeController;
use App\Livewire\Onboarding;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('onboarding', Onboarding::class)->name('onboarding');

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // Slide export routes
    Route::get('slides/export/powerpoint', [\App\Http\Controllers\SlideExportController::class, 'exportPowerPoint'])->name('slides.export.powerpoint');
    Route::get('slides/export/pdf', [\App\Http\Controllers\SlideExportController::class, 'exportPdf'])->name('slides.export.pdf');

    // Video Editor Project Routes (with web auth)
    Route::prefix('video-editor')->name('video-editor.')->group(function () {
        Route::post('/upload', [\App\Http\Controllers\Api\VideoEditorController::class, 'upload'])->name('upload');
        Route::post('/auto-detect', [\App\Http\Controllers\Api\VideoEditorController::class, 'autoDetect'])->name('auto-detect');
        Route::post('/export', [\App\Http\Controllers\Api\VideoEditorController::class, 'export'])->name('export');
        Route::get('/projects', [\App\Http\Controllers\Api\VideoEditorController::class, 'projects'])->name('projects.index');
        Route::post('/projects', [\App\Http\Controllers\Api\VideoEditorController::class, 'storeProject'])->name('projects.store');
        Route::get('/projects/{project}', [\App\Http\Controllers\Api\VideoEditorController::class, 'showProject'])->name('projects.show');
        Route::put('/projects/{project}', [\App\Http\Controllers\Api\VideoEditorController::class, 'updateProject'])->name('projects.update');
        Route::delete('/projects/{project}', [\App\Http\Controllers\Api\VideoEditorController::class, 'destroyProject'])->name('projects.destroy');
        Route::delete('/exports/{export}', [\App\Http\Controllers\Api\VideoEditorController::class, 'destroyExport'])->name('exports.destroy');
    });
});

// User Profile Route (public)
Route::get('users/{user}', [\App\Http\Controllers\UserProfileController::class, 'show'])->name('users.show');

// Admin quick approval via signed URL
Route::get('network/approve/{networkMember}', [\App\Http\Controllers\NetworkController::class, 'quickApprove'])
    ->name('network.quick-approve')
    ->middleware('signed');

// Prayer Room Routes
Route::get('prayer-room', [\App\Http\Controllers\PrayerRoomController::class, 'index'])->name('prayer-room.index');
Route::post('prayer-room', [\App\Http\Controllers\PrayerRoomController::class, 'store'])->name('prayer-room.store');

// Kingdom Kids
Route::get('kingdom-kids', [\App\Http\Controllers\KingdomKidsController::class, 'index'])->name('kingdom-kids');

// Download Routes
Route::get('gallery/{gallery}/images/{image}/download', [\App\Http\Controllers\DownloadController::class, 'galleryImage'])->name('gallery.image.download');
Route::get('picture-studies/{pictureStudy}/download', [\App\Http\Controllers\DownloadController::class, 'pictureStudy'])->name('picture-study.download');

require __DIR__.'/auth.php';
require __DIR__.'/public.php';
