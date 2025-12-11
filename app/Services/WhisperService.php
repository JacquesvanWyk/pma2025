<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class WhisperService
{
    protected string $pythonPath;

    protected string $model;

    protected string $language;

    protected string $tempDir;

    public function __construct()
    {
        $this->pythonPath = config('video-tools.whisper.python_path');
        $this->model = config('video-tools.whisper.model');
        $this->language = config('video-tools.whisper.language');
        $this->tempDir = config('video-tools.whisper.temp_dir');

        if (! is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0755, true);
        }
    }

    public function transcribe(string $audioPath, ?string $language = null): array
    {
        if (! file_exists($audioPath)) {
            throw new \Exception("Audio file not found: {$audioPath}");
        }

        $language = $language ?? $this->language;
        $outputFile = $this->tempDir.'/'.Str::random(16).'.json';

        $script = $this->buildPythonScript($audioPath, $outputFile, $language);
        $scriptPath = $this->tempDir.'/'.Str::random(16).'.py';
        file_put_contents($scriptPath, $script);

        try {
            $result = Process::timeout(600)
                ->env([
                    'PATH' => '/opt/homebrew/bin:/usr/local/bin:/usr/bin:/bin',
                ])
                ->run([$this->pythonPath, $scriptPath]);

            if (! $result->successful()) {
                Log::error('Whisper transcription failed', [
                    'audio' => $audioPath,
                    'stderr' => $result->errorOutput(),
                    'stdout' => $result->output(),
                ]);
                throw new \Exception('Whisper transcription failed: '.$result->errorOutput());
            }

            if (! file_exists($outputFile)) {
                throw new \Exception('Whisper output file not created');
            }

            $output = json_decode(file_get_contents($outputFile), true);

            return $this->parseWhisperOutput($output);
        } finally {
            @unlink($scriptPath);
            @unlink($outputFile);
        }
    }

    protected function buildPythonScript(string $audioPath, string $outputPath, string $language): string
    {
        $audioPath = addslashes($audioPath);
        $outputPath = addslashes($outputPath);
        $model = $this->model;

        return <<<PYTHON
import whisper_timestamped as whisper
import json

audio = whisper.load_audio("{$audioPath}")
model = whisper.load_model("{$model}", device="cpu")

result = whisper.transcribe(
    model,
    audio,
    language="{$language}",
    detect_disfluencies=False,
    vad=True,
)

with open("{$outputPath}", "w") as f:
    json.dump(result, f, indent=2, ensure_ascii=False)

print("Transcription complete")
PYTHON;
    }

    protected function parseWhisperOutput(array $output): array
    {
        $segments = [];

        foreach ($output['segments'] ?? [] as $index => $segment) {
            $words = [];

            foreach ($segment['words'] ?? [] as $word) {
                $words[] = [
                    'text' => $word['text'] ?? '',
                    'start' => $word['start'] ?? 0,
                    'end' => $word['end'] ?? 0,
                    'confidence' => $word['confidence'] ?? 0,
                ];
            }

            $segments[] = [
                'order' => $index,
                'text' => trim($segment['text'] ?? ''),
                'start_seconds' => $segment['start'] ?? 0,
                'end_seconds' => $segment['end'] ?? 0,
                'start_ms' => (int) (($segment['start'] ?? 0) * 1000),
                'end_ms' => (int) (($segment['end'] ?? 0) * 1000),
                'words' => $words,
            ];
        }

        return [
            'text' => $output['text'] ?? '',
            'language' => $output['language'] ?? $this->language,
            'segments' => $segments,
        ];
    }

    public function transcribeToLyricTimestamps(string $audioPath, ?string $language = null): array
    {
        $result = $this->transcribe($audioPath, $language);
        $timestamps = [];

        foreach ($result['segments'] as $index => $segment) {
            $timestamps[] = [
                'order' => $index,
                'text' => $segment['text'],
                'start_ms' => $segment['start_ms'],
                'end_ms' => $segment['end_ms'],
                'start_seconds' => $segment['start_seconds'],
                'end_seconds' => $segment['end_seconds'],
                'section' => $this->detectSection($segment['text']),
                'animation' => 'fade',
            ];
        }

        return $timestamps;
    }

    protected function detectSection(string $text): ?string
    {
        $text = strtolower($text);

        if (str_contains($text, '[verse') || preg_match('/^verse\s*\d/i', $text)) {
            return 'verse';
        }
        if (str_contains($text, '[chorus') || str_contains($text, 'chorus')) {
            return 'chorus';
        }
        if (str_contains($text, '[bridge') || str_contains($text, 'bridge')) {
            return 'bridge';
        }
        if (str_contains($text, '[intro') || str_contains($text, 'intro')) {
            return 'intro';
        }
        if (str_contains($text, '[outro') || str_contains($text, 'outro')) {
            return 'outro';
        }

        return null;
    }

    public function isAvailable(): bool
    {
        if (! file_exists($this->pythonPath)) {
            return false;
        }

        try {
            $result = Process::timeout(30)->run([
                $this->pythonPath,
                '-c',
                'import whisper_timestamped; print("ok")',
            ]);

            return $result->successful() && str_contains($result->output(), 'ok');
        } catch (\Exception $e) {
            return false;
        }
    }
}
