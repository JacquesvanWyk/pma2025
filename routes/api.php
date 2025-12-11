<?php

use App\Http\Controllers\Api\KieCallbackController;
use App\Http\Controllers\Api\PledgeController;
use App\Http\Controllers\Api\VideoEditorController;
use App\Models\Short;
use Illuminate\Support\Facades\Route;

Route::post('/kie/callback', [KieCallbackController::class, 'handle'])->name('kie.callback');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pledge/update', [PledgeController::class, 'update']);
});

Route::prefix('video-editor')->group(function () {
    Route::post('/upload', [VideoEditorController::class, 'upload']);
    Route::post('/auto-detect', [VideoEditorController::class, 'autoDetect']);
    Route::post('/export', [VideoEditorController::class, 'export']);
});

Route::get('/shorts/{short}', function (Short $short) {
    return response()->json([
        'id' => $short->id,
        'title' => $short->title,
        'video_url' => $short->video_url,
        'youtube_embed' => $short->youtube_embed_url,
    ]);
});
