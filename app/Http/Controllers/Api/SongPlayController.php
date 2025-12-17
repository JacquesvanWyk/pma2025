<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\JsonResponse;

class SongPlayController extends Controller
{
    public function store(Song $song): JsonResponse
    {
        $song->incrementPlayCount();

        return response()->json([
            'success' => true,
            'play_count' => $song->play_count,
        ]);
    }
}
