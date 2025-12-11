<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LocalRemotionService;
use App\Services\WhisperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoEditorController extends Controller
{
    public function __construct(
        protected WhisperService $whisper,
        protected LocalRemotionService $remotion
    ) {}

    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'audio' => 'required|file|mimes:mp3,wav,m4a,ogg,webm|max:51200',
        ]);

        $file = $request->file('audio');
        $filename = 'video-editor/'.uniqid().'.'.$file->getClientOriginalExtension();

        Storage::disk('public')->put($filename, file_get_contents($file));

        return response()->json([
            'success' => true,
            'audio_url' => Storage::disk('public')->url($filename),
        ]);
    }

    public function autoDetect(Request $request): JsonResponse
    {
        $request->validate([
            'audio_url' => 'required|string',
        ]);

        $audioUrl = $request->input('audio_url');

        if (str_starts_with($audioUrl, 'http')) {
            $tempPath = storage_path('app/temp/whisper/'.uniqid().'.mp3');
            file_put_contents($tempPath, file_get_contents($audioUrl));
            $audioPath = $tempPath;
        } else {
            $audioPath = Storage::disk('public')->path(ltrim($audioUrl, '/storage/'));
        }

        try {
            $timestamps = $this->whisper->transcribeToLyricTimestamps($audioPath);

            return response()->json([
                'success' => true,
                'timestamps' => $timestamps,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        } finally {
            if (isset($tempPath) && file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    public function export(Request $request): JsonResponse
    {
        $request->validate([
            'audio_url' => 'required|string',
            'lyrics' => 'required|array',
            'style' => 'required|array',
            'width' => 'required|integer',
            'height' => 'required|integer',
            'duration_ms' => 'required|integer',
        ]);

        try {
            $result = $this->remotion->renderLyricVideo([
                'lyrics' => $request->input('lyrics'),
                'audio_url' => $request->input('audio_url'),
                'background_color' => $request->input('style.backgroundColor', '#000000'),
                'background_image' => $request->input('style.backgroundImage'),
                'font_family' => $request->input('style.fontFamily', 'Arial'),
                'font_size' => $request->input('style.fontSize', 48),
                'font_color' => $request->input('style.fontColor', '#ffffff'),
                'text_position' => $request->input('style.textPosition', 'center'),
                'text_shadow' => $request->input('style.textShadow', true),
                'duration_ms' => $request->input('duration_ms'),
                'width' => $request->input('width'),
                'height' => $request->input('height'),
                'fps' => 30,
            ]);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'download_url' => Storage::disk('public')->url($result['output_path']),
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Render failed',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
