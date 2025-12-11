# WhisperService Documentation

## Overview

The `WhisperService` provides audio transcription with word-level timestamps using the [whisper-timestamped](https://github.com/linto-ai/whisper-timestamped) library. This is used primarily for auto-detecting lyric timestamps from audio files for lyric video generation.

## Requirements

### Local Installation

The service requires Python with whisper-timestamped installed:

```bash
# Create virtual environment
python3 -m venv ~/whisper-env
source ~/whisper-env/bin/activate

# Install whisper-timestamped
pip install whisper-timestamped
```

### Configuration

Configuration is managed in `config/video-tools.php`:

```php
'whisper' => [
    'python_path' => env('WHISPER_PYTHON_PATH', '/Users/jacquesvanwyk/whisper-env/bin/python3'),
    'model' => env('WHISPER_MODEL', 'base'),      // tiny, base, small, medium, large
    'language' => env('WHISPER_LANGUAGE', 'en'),
    'temp_dir' => storage_path('app/temp/whisper'),
],
```

### Environment Variables

Add to `.env`:

```env
WHISPER_PYTHON_PATH=/path/to/whisper-env/bin/python3
WHISPER_MODEL=base
WHISPER_LANGUAGE=en
```

## Usage

### Basic Transcription

```php
use App\Services\WhisperService;

$whisper = app(WhisperService::class);

// Full transcription with all data
$result = $whisper->transcribe('/path/to/audio.mp3');

// Returns:
[
    'text' => 'Full transcription text...',
    'language' => 'en',
    'segments' => [
        [
            'order' => 0,
            'text' => 'First line of lyrics',
            'start_seconds' => 0.0,
            'end_seconds' => 2.5,
            'start_ms' => 0,
            'end_ms' => 2500,
            'words' => [
                ['text' => 'First', 'start' => 0.0, 'end' => 0.3, 'confidence' => 0.95],
                // ...
            ],
        ],
        // ...
    ],
]
```

### Get Lyric Timestamps (Simplified)

For direct integration with VideoProject lyric timestamps:

```php
$timestamps = $whisper->transcribeToLyricTimestamps('/path/to/audio.mp3');

// Returns array ready for LyricTimestamp model:
[
    [
        'order' => 0,
        'text' => 'First line',
        'start_ms' => 0,
        'end_ms' => 2500,
        'start_seconds' => 0.0,
        'end_seconds' => 2.5,
        'section' => null,    // Detected if line contains [Verse], [Chorus], etc.
        'animation' => 'fade',
    ],
    // ...
]
```

### Check Availability

```php
if ($whisper->isAvailable()) {
    // Whisper is properly installed and configured
}
```

## Models

| Model | Size | Speed | Accuracy | Use Case |
|-------|------|-------|----------|----------|
| tiny | 39M | Fastest | Basic | Quick tests |
| base | 74M | Fast | Good | Default, balanced |
| small | 244M | Medium | Better | Production |
| medium | 769M | Slow | High | High accuracy needed |
| large | 1550M | Slowest | Best | Maximum accuracy |

## Supported Languages

Common language codes:
- `en` - English
- `es` - Spanish
- `fr` - French
- `de` - German
- `pt` - Portuguese
- `zh` - Chinese
- `ja` - Japanese

## Error Handling

```php
try {
    $result = $whisper->transcribe($audioPath);
} catch (\Exception $e) {
    // Handle: file not found, Python error, timeout
    Log::error('Transcription failed', ['error' => $e->getMessage()]);
}
```

## VPS Deployment

For production VPS:

```bash
# Install Python 3.9+
sudo apt update
sudo apt install python3.9 python3.9-venv python3-pip ffmpeg

# Create virtual environment
python3.9 -m venv /opt/whisper-env
source /opt/whisper-env/bin/activate
pip install whisper-timestamped

# Update .env
WHISPER_PYTHON_PATH=/opt/whisper-env/bin/python3
```

## Performance Notes

- First transcription downloads the model (~74MB for base)
- Subsequent calls are faster
- Processing time: ~1x real-time for base model
- Use `tiny` for testing, `base` or `small` for production
