# LocalRemotionService Documentation

## Overview

The `LocalRemotionService` provides programmatic video rendering using [Remotion](https://www.remotion.dev/). It renders React-based video compositions for lyric videos, scripture animations, and other video content.

## Requirements

### Local Installation

The Remotion project is located at `~/remotion-renderer`:

```bash
cd ~/remotion-renderer
npm install
```

### Project Structure

```
~/remotion-renderer/
├── src/
│   ├── index.ts              # Entry point
│   ├── Root.tsx              # Composition registry
│   ├── compositions/
│   │   ├── LyricVideo.tsx    # Lyric video with timestamps
│   │   └── ScriptureVideo.tsx # Animated Bible verses
│   └── components/
│       └── AnimatedText.tsx  # Text animation component
├── package.json
├── tsconfig.json
└── remotion.config.ts
```

### Configuration

Configuration in `config/video-tools.php`:

```php
'remotion' => [
    'project_path' => env('REMOTION_PROJECT_PATH', '/Users/jacquesvanwyk/remotion-renderer'),
    'node_path' => env('REMOTION_NODE_PATH', '/usr/local/bin/node'),
    'temp_dir' => storage_path('app/temp/remotion'),
    'concurrency' => env('REMOTION_CONCURRENCY', 2),
],
```

### Environment Variables

```env
REMOTION_PROJECT_PATH=/path/to/remotion-renderer
REMOTION_NODE_PATH=/usr/local/bin/node
REMOTION_CONCURRENCY=2
```

## Usage

### Render Lyric Video

```php
use App\Services\LocalRemotionService;

$remotion = app(LocalRemotionService::class);

$result = $remotion->renderLyricVideo([
    'lyrics' => [
        [
            'text' => 'First line of the song',
            'start_ms' => 0,
            'end_ms' => 3000,
            'animation' => 'fade',     // fade, slide, karaoke, typewriter
            'position' => 'center',    // top, center, bottom
        ],
        [
            'text' => 'Second line of the song',
            'start_ms' => 3000,
            'end_ms' => 6000,
        ],
    ],
    'audio_url' => 'https://example.com/song.mp3',  // or local path
    'background_color' => '#000000',
    'background_image' => null,  // Optional image URL
    'font_family' => 'Arial',
    'font_size' => 48,
    'font_color' => '#ffffff',
    'text_position' => 'center',
    'text_shadow' => true,
    'duration_ms' => 180000,  // 3 minutes
    'width' => 1920,
    'height' => 1080,
    'fps' => 30,
]);

// Returns:
[
    'success' => true,
    'output_path' => 'videos/abc123xyz.mp4',
    'output_size_bytes' => 15000000,
]
```

### Render Scripture Video

```php
$result = $remotion->renderScriptureVideo([
    'scripture' => 'For God so loved the world that he gave his one and only Son...',
    'reference' => 'John 3:16',
    'background_color' => '#1a1a2e',
    'background_image' => null,
    'text_color' => '#ffffff',
    'duration' => 10,  // seconds
    'width' => 1920,
    'height' => 1080,
    'fps' => 30,
]);
```

### Check Availability

```php
if ($remotion->isAvailable()) {
    // Remotion is properly installed
}
```

### Get Available Compositions

```php
$compositions = $remotion->getAvailableCompositions();

// Returns:
[
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
        // ...
    ],
]
```

## Available Compositions

### LyricVideo

Renders lyrics synced to audio with various text animations.

**Props:**
| Prop | Type | Required | Description |
|------|------|----------|-------------|
| lyrics | array | Yes | Array of lyric lines with timestamps |
| audioUrl | string | Yes | URL or path to audio file |
| backgroundColor | string | No | Hex color (default: #000000) |
| backgroundImage | string | No | URL to background image |
| textStyle | object | Yes | Font family, size, color, position |

**Animation Types:**
- `fade` - Fade in/out (default)
- `slide` - Slide up with fade
- `karaoke` - Progressive color fill
- `typewriter` - Character-by-character reveal

**Position Options:**
- `top` - 10% from top
- `center` - Vertically centered
- `bottom` - 15% from bottom

### ScriptureVideo

Renders animated Bible verses with elegant fade effects.

**Props:**
| Prop | Type | Required | Description |
|------|------|----------|-------------|
| scripture | string | Yes | The Bible verse text |
| reference | string | Yes | Book, chapter:verse |
| backgroundColor | string | No | Hex color (default: #1a1a2e) |
| textColor | string | No | Hex color (default: #ffffff) |
| duration | number | Yes | Duration in seconds |

## Output Sizes

Common video dimensions:

| Name | Width | Height | Aspect Ratio | Use Case |
|------|-------|--------|--------------|----------|
| YouTube | 1920 | 1080 | 16:9 | YouTube, Web |
| YouTube Shorts | 1080 | 1920 | 9:16 | Shorts, Reels |
| Instagram | 1080 | 1080 | 1:1 | Square posts |
| TikTok | 1080 | 1920 | 9:16 | TikTok, Stories |

## CLI Commands

From the Remotion project directory:

```bash
# Open Remotion Studio (preview)
npm run studio

# Render a composition
npx remotion render src/index.ts LyricVideo output.mp4 --props=props.json

# List compositions
npx remotion compositions src/index.ts
```

## VPS Deployment

```bash
# Install Node.js 18+
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Clone/copy Remotion project
git clone <repo> /opt/remotion-renderer
cd /opt/remotion-renderer
npm install

# Install Chrome for Remotion
npx remotion browser ensure

# Update .env
REMOTION_PROJECT_PATH=/opt/remotion-renderer
```

## Adding New Compositions

1. Create component in `src/compositions/`:

```tsx
import React from "react";
import { AbsoluteFill } from "remotion";
import { z } from "zod";

export const myVideoSchema = z.object({
  title: z.string(),
  // ... props
});

export type MyVideoProps = z.infer<typeof myVideoSchema>;

export const MyVideo: React.FC<MyVideoProps> = ({ title }) => {
  return (
    <AbsoluteFill>
      <h1>{title}</h1>
    </AbsoluteFill>
  );
};
```

2. Register in `src/Root.tsx`:

```tsx
import { MyVideo, myVideoSchema } from "./compositions/MyVideo";

<Composition
  id="MyVideo"
  component={MyVideo}
  schema={myVideoSchema}
  // ...
/>
```

3. Add render method in `LocalRemotionService.php`

## Performance Notes

- First render downloads Chromium (~300MB)
- Rendering is CPU/memory intensive
- Use concurrency setting for parallel renders
- Consider queue jobs for long videos
