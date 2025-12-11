<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalRemotionService
{
    protected string $projectPath;

    protected string $nodePath;

    protected string $tempDir;

    protected string $outputDir;

    protected string $outputDisk;

    public function __construct()
    {
        $this->projectPath = config('video-tools.remotion.project_path');
        $this->nodePath = config('video-tools.remotion.node_path', 'node');
        $this->tempDir = config('video-tools.remotion.temp_dir');
        $this->outputDir = config('video-tools.output.video_dir', 'videos');
        $this->outputDisk = config('video-tools.output.disk', 'public');

        if (! is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0755, true);
        }
    }

    public function renderLyricVideo(array $data): array
    {
        $outputFilename = $this->outputDir.'/'.Str::random(32).'.mp4';
        $tempOutput = $this->tempDir.'/'.Str::random(16).'.mp4';

        $inputProps = json_encode([
            'lyrics' => array_map(fn ($lyric) => [
                'text' => $lyric['text'],
                'startMs' => $lyric['start_ms'],
                'endMs' => $lyric['end_ms'],
                'animation' => $lyric['animation'] ?? 'fade',
                'position' => $lyric['position'] ?? 'center',
            ], $data['lyrics']),
            'audioUrl' => $data['audio_url'] ?? '',
            'backgroundColor' => $data['background_color'] ?? '#000000',
            'backgroundImage' => $data['background_image'] ?? null,
            'textStyle' => [
                'fontFamily' => $data['font_family'] ?? 'Arial',
                'fontSize' => $data['font_size'] ?? 48,
                'color' => $data['font_color'] ?? '#ffffff',
                'position' => $data['text_position'] ?? 'center',
                'shadow' => $data['text_shadow'] ?? true,
            ],
        ]);

        $propsFile = $this->tempDir.'/'.Str::random(16).'.json';
        file_put_contents($propsFile, $inputProps);

        try {
            $durationFrames = $this->calculateDurationFrames(
                $data['duration_ms'] ?? 60000,
                $data['fps'] ?? 30
            );

            $width = $data['width'] ?? 1920;
            $height = $data['height'] ?? 1080;
            $fps = $data['fps'] ?? 30;

            $command = [
                'npx',
                'remotion',
                'render',
                'src/index.ts',
                'LyricVideo',
                $tempOutput,
                '--props='.$propsFile,
                '--width='.$width,
                '--height='.$height,
                '--fps='.$fps,
                '--frames=0-'.($durationFrames - 1),
            ];

            $result = Process::timeout(600)
                ->path($this->projectPath)
                ->run($command);

            if (! $result->successful()) {
                Log::error('Remotion render failed', [
                    'command' => implode(' ', $command),
                    'stderr' => $result->errorOutput(),
                    'stdout' => $result->output(),
                ]);
                throw new \Exception('Remotion render failed: '.$result->errorOutput());
            }

            if (! file_exists($tempOutput)) {
                throw new \Exception('Remotion output file not created');
            }

            Storage::disk($this->outputDisk)->put(
                $outputFilename,
                file_get_contents($tempOutput)
            );

            $fileSize = filesize($tempOutput);
            unlink($tempOutput);

            return [
                'success' => true,
                'output_path' => $outputFilename,
                'output_size_bytes' => $fileSize,
            ];
        } finally {
            @unlink($propsFile);
            if (file_exists($tempOutput)) {
                @unlink($tempOutput);
            }
        }
    }

    public function renderScriptureVideo(array $data): array
    {
        $outputFilename = $this->outputDir.'/'.Str::random(32).'.mp4';
        $tempOutput = $this->tempDir.'/'.Str::random(16).'.mp4';

        $duration = $data['duration'] ?? 10;
        $fps = $data['fps'] ?? 30;
        $durationFrames = $duration * $fps;

        $inputProps = json_encode([
            'scripture' => $data['scripture'],
            'reference' => $data['reference'],
            'backgroundColor' => $data['background_color'] ?? '#1a1a2e',
            'backgroundImage' => $data['background_image'] ?? null,
            'textColor' => $data['text_color'] ?? '#ffffff',
            'duration' => $duration,
        ]);

        $propsFile = $this->tempDir.'/'.Str::random(16).'.json';
        file_put_contents($propsFile, $inputProps);

        try {
            $width = $data['width'] ?? 1920;
            $height = $data['height'] ?? 1080;

            $command = [
                'npx',
                'remotion',
                'render',
                'src/index.ts',
                'ScriptureVideo',
                $tempOutput,
                '--props='.$propsFile,
                '--width='.$width,
                '--height='.$height,
                '--fps='.$fps,
                '--frames=0-'.($durationFrames - 1),
            ];

            $result = Process::timeout(300)
                ->path($this->projectPath)
                ->run($command);

            if (! $result->successful()) {
                throw new \Exception('Remotion render failed: '.$result->errorOutput());
            }

            if (! file_exists($tempOutput)) {
                throw new \Exception('Remotion output file not created');
            }

            Storage::disk($this->outputDisk)->put(
                $outputFilename,
                file_get_contents($tempOutput)
            );

            $fileSize = filesize($tempOutput);
            unlink($tempOutput);

            return [
                'success' => true,
                'output_path' => $outputFilename,
                'output_size_bytes' => $fileSize,
            ];
        } finally {
            @unlink($propsFile);
            if (file_exists($tempOutput)) {
                @unlink($tempOutput);
            }
        }
    }

    protected function calculateDurationFrames(int $durationMs, int $fps): int
    {
        return (int) ceil(($durationMs / 1000) * $fps);
    }

    public function isAvailable(): bool
    {
        if (! is_dir($this->projectPath)) {
            return false;
        }

        try {
            $result = Process::timeout(30)
                ->path($this->projectPath)
                ->run(['npx', 'remotion', '--version']);

            return $result->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAvailableCompositions(): array
    {
        return [
            'LyricVideo' => [
                'name' => 'Lyric Video',
                'description' => 'Animated lyrics synced to audio',
                'default_width' => 1920,
                'default_height' => 1080,
                'default_fps' => 30,
            ],
            'ScriptureVideo' => [
                'name' => 'Scripture Video',
                'description' => 'Animated Bible verses with fade effects',
                'default_width' => 1920,
                'default_height' => 1080,
                'default_fps' => 30,
            ],
        ];
    }
}
