<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneratedMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class KieCallbackController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $taskId = $request->input('taskId');
        $status = $request->input('status');
        $data = $request->input('data') ?? $request->all();

        Log::info('KIE Callback Received', [
            'task_id' => $taskId,
            'status' => $status,
            'data_keys' => array_keys($data),
        ]);

        if (! $taskId) {
            return response()->json(['success' => false, 'message' => 'Missing taskId']);
        }

        $media = GeneratedMedia::where('task_id', $taskId)->first();

        if (! $media) {
            Log::warning('KIE Callback for unknown task', ['task_id' => $taskId]);

            return response()->json(['success' => true, 'message' => 'Task not found']);
        }

        if (in_array($status, ['SUCCESS', 'FIRST_SUCCESS'])) {
            $this->handleSuccess($media, $data);
        } elseif (str_contains($status ?? '', 'FAILED') || str_contains($status ?? '', 'ERROR')) {
            $this->handleFailure($media, $status ?? 'Unknown error');
        }

        return response()->json(['success' => true]);
    }

    protected function handleSuccess(GeneratedMedia $media, array $data): void
    {
        $tracks = [];

        if (isset($data['sunoData'])) {
            $tracks = collect($data['sunoData'])->map(fn ($track) => [
                'id' => $track['id'] ?? null,
                'audio_url' => $track['audioUrl'] ?? null,
                'image_url' => $track['imageUrl'] ?? null,
                'title' => $track['title'] ?? null,
                'duration' => $track['duration'] ?? null,
            ])->toArray();
        }

        if (isset($data['videoUrl'])) {
            $media->update([
                'status' => 'completed',
                'remote_url' => $data['videoUrl'],
            ]);
        }

        if (isset($data['imageUrl'])) {
            $media->update([
                'status' => 'completed',
                'remote_url' => $data['imageUrl'],
            ]);
        }

        if (! empty($tracks)) {
            $media->update([
                'status' => 'completed',
                'remote_url' => $tracks[0]['audio_url'] ?? null,
                'metadata' => ['tracks' => $tracks],
            ]);

            Cache::put(
                "music-generation-callback-{$media->id}",
                ['tracks' => $tracks],
                now()->addMinutes(30)
            );
        }

        Log::info('KIE Generation Completed via Callback', [
            'media_id' => $media->id,
            'type' => $media->type,
        ]);
    }

    protected function handleFailure(GeneratedMedia $media, string $status): void
    {
        $media->update([
            'status' => 'failed',
            'metadata' => ['error' => $status],
        ]);

        Log::error('KIE Generation Failed via Callback', [
            'media_id' => $media->id,
            'status' => $status,
        ]);
    }
}
