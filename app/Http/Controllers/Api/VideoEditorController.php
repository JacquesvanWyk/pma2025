<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LyricTimestamp;
use App\Models\VideoExport;
use App\Models\VideoProject;
use App\Services\LocalRemotionService;
use App\Services\WhisperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'type' => 'required|string|in:background,logo',
        ]);

        $file = $request->file('image');
        $type = $request->input('type');
        $directory = $type === 'logo' ? 'video-editor/logos' : 'video-editor/backgrounds';
        $filename = $directory.'/'.uniqid().'.'.$file->getClientOriginalExtension();

        Storage::disk('public')->put($filename, file_get_contents($file));

        return response()->json([
            'success' => true,
            'url' => Storage::disk('public')->url($filename),
        ]);
    }

    public function uploadVideo(Request $request): JsonResponse
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,webm,mov,ogg|max:102400',
        ]);

        $file = $request->file('video');
        $filename = 'video-editor/backgrounds/'.uniqid().'.'.$file->getClientOriginalExtension();

        Storage::disk('public')->put($filename, file_get_contents($file));

        return response()->json([
            'success' => true,
            'url' => Storage::disk('public')->url($filename),
        ]);
    }

    public function autoDetect(Request $request): JsonResponse
    {
        $request->validate([
            'audio_url' => 'required|string',
            'reference_lyrics' => 'nullable|string',
        ]);

        $audioUrl = $request->input('audio_url');
        $referenceLyrics = $request->input('reference_lyrics');

        if (str_starts_with($audioUrl, 'http')) {
            $tempPath = storage_path('app/temp/whisper/'.uniqid().'.mp3');
            file_put_contents($tempPath, file_get_contents($audioUrl));
            $audioPath = $tempPath;
        } else {
            $audioPath = Storage::disk('public')->path(ltrim($audioUrl, '/storage/'));
        }

        try {
            $timestamps = $this->whisper->transcribeToLyricTimestamps($audioPath);

            $corrected = false;

            Log::info('Auto-detect completed', [
                'timestamps_count' => count($timestamps),
                'has_reference_lyrics' => ! empty($referenceLyrics),
                'reference_lyrics_length' => $referenceLyrics ? strlen($referenceLyrics) : 0,
            ]);

            if ($referenceLyrics && ! empty(trim($referenceLyrics)) && ! empty($timestamps)) {
                Log::info('Attempting AI correction with reference lyrics');
                $correctedTimestamps = $this->correctLyricsWithAI($timestamps, $referenceLyrics);
                if ($correctedTimestamps) {
                    $timestamps = $correctedTimestamps;
                    $corrected = true;
                    Log::info('AI correction successful', ['corrected_count' => count($correctedTimestamps)]);
                } else {
                    Log::warning('AI correction returned null');
                }
            }

            return response()->json([
                'success' => true,
                'timestamps' => $timestamps,
                'corrected' => $corrected,
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

    private function correctLyricsWithAI(array $detectedTimestamps, string $referenceLyrics): ?array
    {
        $detectedText = collect($detectedTimestamps)
            ->map(fn ($t, $i) => "[$i] {$t['start_ms']}ms - {$t['end_ms']}ms: \"{$t['text']}\"")
            ->join("\n");

        $prompt = <<<PROMPT
DETECTED LYRICS WITH TIMESTAMPS:
{$detectedText}

REFERENCE LYRICS (correct text):
{$referenceLyrics}

Your task:
1. Match each detected line to the correct text from the reference lyrics
2. Keep the timestamps exactly as they are
3. Replace the detected text with the correct text from reference
4. If a detected line doesn't match anything, keep the original
5. Ignore section markers like [Verse 1], [Chorus] etc - don't include them

Return ONLY a JSON array with this exact format, no explanation:
[{"start_ms": 1234, "end_ms": 5678, "text": "corrected lyrics here", "animation": "fade"}]
PROMPT;

        try {
            $response = \Prism\Prism\Prism::text()
                ->using('groq', 'llama-3.3-70b-versatile')
                ->withSystemPrompt('You are a lyrics correction assistant. Fix transcription errors by matching detected lyrics to reference lyrics. Return only valid JSON.')
                ->withPrompt($prompt)
                ->withMaxTokens(4000)
                ->usingTemperature(0.1)
                ->generate();

            $content = $response->text;
            Log::info('AI correction response', ['length' => strlen($content)]);

            $content = preg_replace('/```json\s*/', '', $content);
            $content = preg_replace('/```\s*/', '', $content);
            $content = trim($content);

            $corrected = json_decode($content, true);
            if (is_array($corrected) && ! empty($corrected)) {
                Log::info('AI correction successful', ['count' => count($corrected)]);

                return array_map(fn ($item) => [
                    'start_ms' => (int) ($item['start_ms'] ?? 0),
                    'end_ms' => (int) ($item['end_ms'] ?? 0),
                    'text' => $item['text'] ?? '',
                    'animation' => $item['animation'] ?? 'fade',
                ], $corrected);
            }

            Log::warning('AI correction parse failed', ['error' => json_last_error_msg()]);
        } catch (\Exception $e) {
            Log::error('AI lyrics correction failed: '.$e->getMessage());
        }

        return null;
    }

    public function export(Request $request): JsonResponse
    {
        $request->validate([
            'project_id' => 'nullable|integer|exists:video_projects,id',
            'title' => 'nullable|string|max:255',
            'audio_url' => 'required|string',
            'lyrics' => 'required|array',
            'style' => 'required|array',
            'width' => 'required|integer',
            'height' => 'required|integer',
            'duration_ms' => 'required|integer',
        ]);

        if (! $this->remotion->isAvailable()) {
            return response()->json([
                'success' => false,
                'error' => 'Remotion is not available. Please check that the remotion-renderer project is set up correctly.',
                'error_code' => 'REMOTION_UNAVAILABLE',
            ], 503);
        }

        $projectId = $request->input('project_id');
        $width = $request->input('width');
        $height = $request->input('height');
        $resolution = "{$width}x{$height}";

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
                'width' => $width,
                'height' => $height,
                'fps' => 30,
            ]);

            if ($result['success'] ?? false) {
                $export = null;

                if ($projectId) {
                    $export = VideoExport::create([
                        'video_project_id' => $projectId,
                        'resolution' => $resolution,
                        'file_path' => $result['output_path'],
                        'file_size_bytes' => $result['output_size_bytes'] ?? null,
                        'duration_ms' => $request->input('duration_ms'),
                        'status' => 'completed',
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'status' => 'completed',
                    'downloadUrl' => Storage::disk('public')->url($result['output_path']),
                    'message' => 'Video rendered successfully!',
                    'export' => $export ? [
                        'id' => $export->id,
                        'resolution' => $export->resolution,
                        'file_url' => $export->file_url,
                        'formatted_file_size' => $export->formatted_file_size,
                    ] : null,
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Render failed',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Video export failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function status(Request $request, string $taskId): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => 'Status endpoint not needed for local rendering',
        ], 404);
    }

    public function projects(Request $request): JsonResponse
    {
        $projects = VideoProject::where('user_id', auth()->id())
            ->where('type', 'lyric_video')
            ->withCount('exports')
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn ($project) => [
                'id' => $project->id,
                'name' => $project->name,
                'thumbnail_url' => $project->thumbnail_url,
                'updated_at' => $project->updated_at->toISOString(),
                'exports_count' => $project->exports_count,
            ]);

        return response()->json([
            'success' => true,
            'projects' => $projects,
        ]);
    }

    public function storeProject(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'audio_url' => 'required|string',
            'audio_duration_ms' => 'required|integer',
            'background_type' => 'nullable|string|in:color,image,video',
            'background_color' => 'nullable|string',
            'background_image' => 'nullable|string',
            'background_video' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'logo_position' => 'nullable|string',
            'resolution' => 'nullable|string',
            'text_style' => 'nullable|array',
            'settings' => 'nullable|array',
            'lyrics' => 'nullable|array',
            'reference_lyrics' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $backgroundType = $request->input('background_type', 'color');

            $backgroundValue = match ($backgroundType) {
                'video' => $request->input('background_video'),
                'image' => $request->input('background_image'),
                default => $request->input('background_color', '#000000'),
            };

            $logoUrl = $request->input('logo_url');
            $logoPath = null;
            if ($logoUrl && str_starts_with($logoUrl, '/storage/')) {
                $logoPath = ltrim($logoUrl, '/storage/');
            } elseif ($logoUrl && str_contains($logoUrl, '/storage/')) {
                $logoPath = substr($logoUrl, strpos($logoUrl, '/storage/') + 9);
            }

            $project = VideoProject::create([
                'user_id' => auth()->id(),
                'name' => $request->input('name'),
                'type' => 'lyric_video',
                'status' => 'draft',
                'audio_url' => $request->input('audio_url'),
                'audio_duration_ms' => $request->input('audio_duration_ms'),
                'background_type' => $backgroundType,
                'background_value' => $backgroundValue,
                'logo_path' => $logoPath,
                'logo_position' => $request->input('logo_position', 'bottom-right'),
                'resolution' => $request->input('resolution', '1920x1080'),
                'text_style' => $request->input('text_style', []),
                'settings' => $request->input('settings', []),
                'reference_lyrics' => $request->input('reference_lyrics'),
            ]);

            if ($request->has('lyrics')) {
                foreach ($request->input('lyrics') as $index => $lyric) {
                    LyricTimestamp::create([
                        'video_project_id' => $project->id,
                        'order' => $lyric['order'] ?? $index,
                        'text' => $lyric['text'] ?? '',
                        'start_ms' => $lyric['start_ms'] ?? 0,
                        'end_ms' => $lyric['end_ms'] ?? 0,
                        'animation' => $lyric['animation'] ?? 'fade',
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'project' => [
                    'id' => $project->id,
                    'name' => $project->name,
                ],
            ]);
        });
    }

    public function showProject(VideoProject $project): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
            ], 403);
        }

        $project->load(['lyricTimestamps', 'exports']);

        $backgroundType = $project->background_type ?? 'color';
        $backgroundValue = $project->background_value;

        $backgroundUrl = null;
        if ($backgroundType !== 'color' && $backgroundValue) {
            if (str_starts_with($backgroundValue, 'http') || str_starts_with($backgroundValue, '/storage/')) {
                $backgroundUrl = $backgroundValue;
            } else {
                $backgroundUrl = Storage::disk('public')->url($backgroundValue);
            }
        }

        return response()->json([
            'success' => true,
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'audio_url' => $project->audio_source_url,
                'audio_duration_ms' => $project->audio_duration_ms,
                'background_type' => $backgroundType,
                'background_color' => $backgroundType === 'color' ? $backgroundValue : null,
                'background_image' => $backgroundType === 'image' ? $backgroundUrl : null,
                'background_video' => $backgroundType === 'video' ? $backgroundUrl : null,
                'logo_url' => $project->logo_url,
                'logo_position' => $project->logo_position,
                'resolution' => $project->resolution,
                'text_style' => $project->text_style ?? [],
                'settings' => $project->settings ?? [],
                'reference_lyrics' => $project->reference_lyrics,
                'lyrics' => $project->lyricTimestamps->map(fn ($lyric) => [
                    'order' => $lyric->order,
                    'text' => $lyric->text,
                    'start_ms' => $lyric->start_ms,
                    'end_ms' => $lyric->end_ms,
                    'animation' => $lyric->animation,
                ]),
                'exports' => $project->exports->map(fn ($export) => [
                    'id' => $export->id,
                    'resolution' => $export->resolution,
                    'file_url' => $export->file_url,
                    'file_size_bytes' => $export->file_size_bytes,
                    'formatted_file_size' => $export->formatted_file_size,
                    'created_at' => $export->created_at->toISOString(),
                ]),
            ],
        ]);
    }

    public function updateProject(Request $request, VideoProject $project): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'audio_url' => 'sometimes|string',
            'audio_duration_ms' => 'sometimes|integer',
            'background_type' => 'nullable|string|in:color,image,video',
            'background_color' => 'nullable|string',
            'background_image' => 'nullable|string',
            'background_video' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'logo_position' => 'nullable|string',
            'resolution' => 'nullable|string',
            'text_style' => 'nullable|array',
            'settings' => 'nullable|array',
            'lyrics' => 'nullable|array',
            'reference_lyrics' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $project) {
            $backgroundType = $request->input('background_type', $project->background_type ?? 'color');

            $backgroundValue = match ($backgroundType) {
                'video' => $request->input('background_video'),
                'image' => $request->input('background_image'),
                default => $request->input('background_color', '#000000'),
            };

            $logoUrl = $request->input('logo_url');
            $logoPath = $project->logo_path;
            if ($logoUrl) {
                if (str_starts_with($logoUrl, '/storage/')) {
                    $logoPath = ltrim($logoUrl, '/storage/');
                } elseif (str_contains($logoUrl, '/storage/')) {
                    $logoPath = substr($logoUrl, strpos($logoUrl, '/storage/') + 9);
                }
            }

            $updateData = [
                'name' => $request->input('name', $project->name),
                'audio_url' => $request->input('audio_url', $project->audio_url),
                'audio_duration_ms' => $request->input('audio_duration_ms', $project->audio_duration_ms),
                'background_type' => $backgroundType,
                'background_value' => $backgroundValue,
                'logo_path' => $logoPath,
                'logo_position' => $request->input('logo_position', $project->logo_position),
                'resolution' => $request->input('resolution', $project->resolution),
                'text_style' => $request->input('text_style', $project->text_style),
                'settings' => $request->input('settings', $project->settings),
                'reference_lyrics' => $request->input('reference_lyrics', $project->reference_lyrics),
            ];

            $project->update($updateData);

            if ($request->has('lyrics')) {
                $project->lyricTimestamps()->delete();

                foreach ($request->input('lyrics') as $index => $lyric) {
                    LyricTimestamp::create([
                        'video_project_id' => $project->id,
                        'order' => $lyric['order'] ?? $index,
                        'text' => $lyric['text'] ?? '',
                        'start_ms' => $lyric['start_ms'] ?? 0,
                        'end_ms' => $lyric['end_ms'] ?? 0,
                        'animation' => $lyric['animation'] ?? 'fade',
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'project' => [
                    'id' => $project->id,
                    'name' => $project->name,
                ],
            ]);
        });
    }

    public function destroyProject(VideoProject $project): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
            ], 403);
        }

        foreach ($project->exports as $export) {
            if ($export->file_path) {
                Storage::disk('public')->delete($export->file_path);
            }
            if ($export->thumbnail_path) {
                Storage::disk('public')->delete($export->thumbnail_path);
            }
        }

        $project->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function destroyExport(VideoExport $export): JsonResponse
    {
        $project = $export->videoProject;

        if ($project->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
            ], 403);
        }

        if ($export->file_path) {
            Storage::disk('public')->delete($export->file_path);
        }
        if ($export->thumbnail_path) {
            Storage::disk('public')->delete($export->thumbnail_path);
        }

        $export->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
