<?php

use App\Http\Controllers\Api\PledgeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pledge/update', [PledgeController::class, 'update']);
});
