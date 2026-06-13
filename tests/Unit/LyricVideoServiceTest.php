<?php

use App\Services\LyricVideoService;
use Tests\TestCase;

uses(TestCase::class);

test('it parses lrc timestamps into lyric ranges', function () {
    $service = new LyricVideoService;

    $lines = $service->parseLyricsWithTimestamps(implode("\n", [
        '[00:01.250]First line',
        '[00:03.500]Second line',
    ]));

    expect($lines)->toHaveCount(2)
        ->and($lines[0])->toMatchArray([
            'start_ms' => 1250,
            'end_ms' => 3500,
            'text' => 'First line',
        ])
        ->and($lines[1])->toMatchArray([
            'start_ms' => 3500,
            'end_ms' => 8500,
            'text' => 'Second line',
        ]);
});

test('it reads audio duration via ffprobe output', function () {
    $scriptPath = storage_path('framework/testing/fake-ffprobe.sh');

    if (! is_dir(dirname($scriptPath))) {
        mkdir(dirname($scriptPath), 0755, true);
    }

    file_put_contents($scriptPath, <<<'SH'
#!/bin/sh
printf '{"format":{"duration":"12.345"}}'
SH);
    chmod($scriptPath, 0755);

    config([
        'fluent-ffmpeg.ffprobe_path' => $scriptPath,
        'fluent-ffmpeg.timeout' => 5,
    ]);

    $service = new LyricVideoService;

    expect($service->getAudioDuration('/tmp/fake-audio.mp3'))->toBe(12345);

    unlink($scriptPath);
});
