<?php

use App\Http\Controllers\Api\KieCallbackController;
use App\Http\Controllers\Api\PledgeController;
use App\Http\Controllers\Api\SongPlayController;
use App\Http\Controllers\Api\VideoEditorController;
use App\Models\Short;
use Illuminate\Support\Facades\Route;

Route::post('/kie/callback', [KieCallbackController::class, 'handle'])->name('kie.callback');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pledge/update', [PledgeController::class, 'update']);
});

Route::middleware('web')->group(function () {
    Route::prefix('video-editor')->group(function () {
        Route::post('/upload', [VideoEditorController::class, 'upload']);
        Route::post('/upload-image', [VideoEditorController::class, 'uploadImage']);
        Route::post('/upload-video', [VideoEditorController::class, 'uploadVideo']);
        Route::post('/auto-detect', [VideoEditorController::class, 'autoDetect']);
        Route::post('/export', [VideoEditorController::class, 'export']);
        Route::get('/status/{taskId}', [VideoEditorController::class, 'status']);

        Route::get('/projects', [VideoEditorController::class, 'projects']);
        Route::post('/projects', [VideoEditorController::class, 'storeProject']);
        Route::get('/projects/{project}', [VideoEditorController::class, 'showProject']);
        Route::put('/projects/{project}', [VideoEditorController::class, 'updateProject']);
        Route::delete('/projects/{project}', [VideoEditorController::class, 'destroyProject']);
        Route::delete('/exports/{export}', [VideoEditorController::class, 'destroyExport']);
    });
});

Route::get('/shorts/{short}', function (Short $short) {
    return response()->json([
        'id' => $short->id,
        'title' => $short->title,
        'video_url' => $short->video_url,
        'youtube_embed' => $short->youtube_embed_url,
    ]);
});

Route::post('/songs/{song}/play', [SongPlayController::class, 'store'])->name('songs.play');
