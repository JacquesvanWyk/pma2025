<?php

use App\Http\Controllers\Api\PledgeController;
use App\Models\Short;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pledge/update', [PledgeController::class, 'update']);
});

Route::get('/shorts/{short}', function (Short $short) {
    return response()->json([
        'id' => $short->id,
        'title' => $short->title,
        'video_url' => $short->video_url,
        'youtube_embed' => $short->youtube_embed_url,
    ]);
});
