<?php

namespace App\Services;

use App\Models\VideoProject;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ritechoice23\FluentFFmpeg\Facades\FFmpeg;

class LyricVideoService
{
    protected string $tempDir;

    protected string $outputDisk;

    public function __construct()
    {
        $this->tempDir = storage_path('app/temp/video');
        $this->outputDisk = config('kie.storage.disk', 'public');

        if (! is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0755, true);
        }
    }

    public function generateLyricVideo(VideoProject $project): array
    {
        $project->update([
            'status' => 'processing',
            'processing_started_at' => now(),
            'error_message' => null,
        ]);

        try {
            $audioPath = $this->resolveAudioPath($project);
            if (! $audioPath) {
                throw new \Exception('No audio source available');
            }

            $lyrics = $project->lyricTimestamps()->orderBy('order')->get();
            if ($lyrics->isEmpty()) {
                throw new \Exception('No lyrics timestamps available');
            }

            $dimensions = $project->resolution_dimensions;
            $outputFilename = 'videos/'.Str::random(32).'.mp4';
            $outputPath = $this->tempDir.'/'.Str::random(16).'.mp4';

            $this->createVideoWithLyrics(
                audioPath: $audioPath,
                lyrics: $lyrics,
                project: $project,
                outputPath: $outputPath,
                width: $dimensions['width'],
                height: $dimensions['height']
            );

            if (! file_exists($outputPath)) {
                throw new \Exception('Video generation failed - output file not created');
            }

            Storage::disk($this->outputDisk)->put(
                $outputFilename,
                file_get_contents($outputPath)
            );

            $thumbnailPath = $this->generateThumbnail($outputPath, $project);

            $fileSize = filesize($outputPath);
            unlink($outputPath);

            $project->update([
                'status' => 'completed',
                'output_path' => $outputFilename,
                'thumbnail_path' => $thumbnailPath,
                'output_size_bytes' => $fileSize,
                'output_duration_ms' => $project->audio_duration_ms,
                'processing_completed_at' => now(),
            ]);

            return [
                'success' => true,
                'output_path' => $outputFilename,
                'thumbnail_path' => $thumbnailPath,
            ];
        } catch (\Exception $e) {
            Log::error('Lyric video generation failed', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $project->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'processing_completed_at' => now(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function resolveAudioPath(VideoProject $project): ?string
    {
        if ($project->audio_path) {
            $disk = config('kie.storage.disk', 'public');

            return Storage::disk($disk)->path($project->audio_path);
        }

        if ($project->audioMedia && $project->audioMedia->file_path) {
            $disk = config('kie.storage.disk', 'public');

            return Storage::disk($disk)->path($project->audioMedia->file_path);
        }

        if ($project->audio_url) {
            $tempAudio = $this->tempDir.'/'.Str::random(16).'.mp3';
            $contents = file_get_contents($project->audio_url);
            file_put_contents($tempAudio, $contents);

            return $tempAudio;
        }

        return null;
    }

    protected function createVideoWithLyrics(
        string $audioPath,
        \Illuminate\Support\Collection $lyrics,
        VideoProject $project,
        string $outputPath,
        int $width,
        int $height
    ): void {
        $style = $project->default_text_style;
        $bgType = $project->background_type;
        $bgValue = $project->background_value ?? '#000000';

        $filterComplex = $this->buildFilterComplex(
            lyrics: $lyrics,
            style: $style,
            width: $width,
            height: $height
        );

        $duration = $project->audio_duration_ms / 1000;

        if ($bgType === 'color') {
            $colorBg = ltrim($bgValue, '#');

            $ffmpeg = FFmpeg::fromPath("color=c=0x{$colorBg}:s={$width}x{$height}:d={$duration}:r={$project->fps}")
                ->addInputOption('-f', 'lavfi');
        } elseif ($bgType === 'image' && $bgValue) {
            $bgImagePath = Storage::disk($this->outputDisk)->path($bgValue);

            $ffmpeg = FFmpeg::fromPath("movie={$bgImagePath},scale={$width}:{$height},loop=loop=-1:size=1:start=0")
                ->addInputOption('-f', 'lavfi');
        } else {
            $ffmpeg = FFmpeg::fromPath("color=c=0x000000:s={$width}x{$height}:d={$duration}:r={$project->fps}")
                ->addInputOption('-f', 'lavfi');
        }

        $ffmpeg->addInput($audioPath)
            ->videoCodec('libx264')
            ->audioCodec('aac')
            ->audioBitrate('192k')
            ->videoBitrate('5000k')
            ->encodingPreset('medium')
            ->pixelFormat('yuv420p')
            ->frameRate($project->fps)
            ->addOption('-filter_complex', $filterComplex)
            ->addOption('-map', '[outv]')
            ->addOption('-map', '1:a')
            ->addOption('-shortest')
            ->addOption('-movflags', '+faststart')
            ->save($outputPath);
    }

    protected function buildFilterComplex(
        \Illuminate\Support\Collection $lyrics,
        array $style,
        int $width,
        int $height
    ): string {
        $filters = ['[0:v]null[base]'];
        $lastOutput = 'base';

        foreach ($lyrics as $index => $lyric) {
            $text = $this->escapeText($lyric->text);
            $startTime = $lyric->start_seconds;
            $endTime = $lyric->end_seconds;
            $outputLabel = "v{$index}";

            $fontSize = $style['font_size'] ?? 48;
            $fontColor = $style['font_color'] ?? 'white';
            $font = $style['font'] ?? 'Arial';
            $position = $this->getPositionCoords($style['position'] ?? 'center', $width, $height);

            $animation = $lyric->animation ?? $style['animation'] ?? 'fade';
            $alphaExpr = $this->getAlphaExpression($animation, $startTime, $endTime);

            $drawtext = sprintf(
                "drawtext=text='%s':fontsize=%d:fontcolor=%s@%s:x=%s:y=%s:enable='between(t,%f,%f)'",
                $text,
                $fontSize,
                ltrim($fontColor, '#'),
                $alphaExpr,
                $position['x'],
                $position['y'],
                $startTime,
                $endTime
            );

            if ($style['shadow'] ?? false) {
                $drawtext .= sprintf(
                    ':shadowcolor=black@0.5:shadowx=2:shadowy=2'
                );
            }

            $filters[] = "[{$lastOutput}]{$drawtext}[{$outputLabel}]";
            $lastOutput = $outputLabel;
        }

        $filters[count($filters) - 1] = str_replace(
            "[{$lastOutput}]",
            '[outv]',
            $filters[count($filters) - 1]
        );

        return implode(';', $filters);
    }

    protected function getPositionCoords(string $position, int $width, int $height): array
    {
        return match ($position) {
            'top' => ['x' => '(w-text_w)/2', 'y' => 'h*0.1'],
            'top-left' => ['x' => '50', 'y' => 'h*0.1'],
            'top-right' => ['x' => 'w-text_w-50', 'y' => 'h*0.1'],
            'center' => ['x' => '(w-text_w)/2', 'y' => '(h-text_h)/2'],
            'bottom' => ['x' => '(w-text_w)/2', 'y' => 'h*0.85'],
            'bottom-left' => ['x' => '50', 'y' => 'h*0.85'],
            'bottom-right' => ['x' => 'w-text_w-50', 'y' => 'h*0.85'],
            default => ['x' => '(w-text_w)/2', 'y' => '(h-text_h)/2'],
        };
    }

    protected function getAlphaExpression(string $animation, float $startTime, float $endTime): string
    {
        $duration = $endTime - $startTime;
        $fadeTime = min(0.3, $duration * 0.1);

        return match ($animation) {
            'fade' => sprintf(
                'if(lt(t,%f),0,if(lt(t,%f),(t-%f)/%f,if(lt(t,%f),1,((%f-t)/%f))))',
                $startTime,
                $startTime + $fadeTime,
                $startTime,
                $fadeTime,
                $endTime - $fadeTime,
                $endTime,
                $fadeTime
            ),
            'slide' => '1',
            'karaoke' => '1',
            'typewriter' => '1',
            default => '1',
        };
    }

    protected function escapeText(string $text): string
    {
        $text = str_replace(["'", ':', '\\'], ["\\'", '\\:', '\\\\'], $text);
        $text = str_replace(["\n", "\r"], [' ', ''], $text);

        return $text;
    }

    protected function generateThumbnail(string $videoPath, VideoProject $project): ?string
    {
        try {
            $thumbnailFilename = 'thumbnails/'.Str::random(32).'.jpg';
            $tempThumbnail = $this->tempDir.'/'.Str::random(16).'.jpg';

            $midpoint = ($project->audio_duration_ms / 1000) / 2;

            FFmpeg::fromPath($videoPath)
                ->seek(sprintf('%02d:%02d:%02d', floor($midpoint / 3600), floor(($midpoint % 3600) / 60), $midpoint % 60))
                ->addOutputOption('vframes', 1)
                ->save($tempThumbnail);

            if (file_exists($tempThumbnail)) {
                Storage::disk($this->outputDisk)->put(
                    $thumbnailFilename,
                    file_get_contents($tempThumbnail)
                );
                unlink($tempThumbnail);

                return $thumbnailFilename;
            }
        } catch (\Exception $e) {
            Log::warning('Thumbnail generation failed', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    public function getAudioDuration(string $path): ?int
    {
        try {
            $mediaInfo = FFmpeg::probe($path);
            $duration = $mediaInfo->duration();

            if ($duration) {
                return (int) ($duration * 1000);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to get audio duration', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    public function parseLyricsWithTimestamps(string $lrcContent): array
    {
        $lines = [];
        $pattern = '/\[(\d{2}):(\d{2})\.(\d{2,3})\](.+)/';

        foreach (explode("\n", $lrcContent) as $line) {
            if (preg_match($pattern, trim($line), $matches)) {
                $minutes = (int) $matches[1];
                $seconds = (int) $matches[2];
                $ms = (int) str_pad($matches[3], 3, '0');

                $startMs = ($minutes * 60 * 1000) + ($seconds * 1000) + $ms;

                $lines[] = [
                    'start_ms' => $startMs,
                    'text' => trim($matches[4]),
                ];
            }
        }

        for ($i = 0; $i < count($lines); $i++) {
            $lines[$i]['end_ms'] = isset($lines[$i + 1])
                ? $lines[$i + 1]['start_ms']
                : $lines[$i]['start_ms'] + 5000;
        }

        return $lines;
    }

    public function generateLrcFromLyrics(string $lyrics, int $totalDurationMs): array
    {
        $lines = array_filter(array_map('trim', explode("\n", $lyrics)));
        $lineCount = count($lines);

        if ($lineCount === 0) {
            return [];
        }

        $avgDurationPerLine = $totalDurationMs / $lineCount;
        $result = [];
        $currentTime = 0;

        foreach ($lines as $index => $line) {
            if (empty($line)) {
                continue;
            }

            $section = $this->detectSection($line);
            $duration = $avgDurationPerLine;

            if (str_starts_with($line, '[') && str_ends_with($line, ']')) {
                $duration = 2000;
            }

            $result[] = [
                'order' => $index,
                'text' => $line,
                'section' => $section,
                'start_ms' => (int) $currentTime,
                'end_ms' => (int) ($currentTime + $duration),
            ];

            $currentTime += $duration;
        }

        return $result;
    }

    protected function detectSection(string $line): ?string
    {
        $line = strtolower($line);

        if (str_contains($line, '[verse') || str_contains($line, 'verse ')) {
            return 'verse';
        }
        if (str_contains($line, '[chorus') || str_contains($line, 'chorus')) {
            return 'chorus';
        }
        if (str_contains($line, '[bridge') || str_contains($line, 'bridge')) {
            return 'bridge';
        }
        if (str_contains($line, '[intro') || str_contains($line, 'intro')) {
            return 'intro';
        }
        if (str_contains($line, '[outro') || str_contains($line, 'outro')) {
            return 'outro';
        }
        if (str_contains($line, '[pre-chorus') || str_contains($line, 'pre-chorus')) {
            return 'pre-chorus';
        }

        return null;
    }

    public function cleanup(): void
    {
        $files = glob($this->tempDir.'/*');
        $threshold = time() - 3600;

        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $threshold) {
                unlink($file);
            }
        }
    }
}
