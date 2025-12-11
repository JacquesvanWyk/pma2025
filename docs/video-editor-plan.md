# Lyric Video Editor - Full Development Plan

> **Vision**: A modern, AI-assisted video editor like DaVinci Resolve for creating lyric videos, scripture animations, and promo content.

---

## Core Features

### Editor Interface
- Full timeline with multiple tracks (audio, lyrics, images, effects)
- Waveform visualization for audio
- Drag-and-drop timestamp adjustment
- Zoom, scrub, playhead controls
- Live preview panel
- Per-line styling (colors, animations, positions)
- Modern, bright, snappy UI

### AI Assistant
- Natural language commands: "Move chorus 2 seconds later"
- Auto-suggestions: "This line seems off-beat, adjust?"
- Style recommendations: "Make verse blue, chorus gold"
- Auto-generate timestamps from audio (Whisper)

### Output Options
- YouTube (16:9 - 1920x1080)
- Instagram/TikTok (9:16 - 1080x1920)
- Square (1:1 - 1080x1080)
- YouTube Shorts (9:16 - 1080x1920)
- Custom sizes

---

## Tech Stack

### Frontend (Editor)
- **Vue.js 3** - Embedded in Filament page
- **WaveSurfer.js** - Audio waveform visualization
- **Konva.js** or **Fabric.js** - Canvas-based timeline
- **Remotion Player** - Video preview (optional)
- **TailwindCSS** - Styling

### Backend (Laravel)
- **Livewire** - Page state, saving/loading, AI integration
- **Whisper** (local Python) - Timestamp detection
- **Remotion** (local Node.js) - Video rendering
- **FFmpeg** - Quick operations, format conversion

### AI Integration
- Claude API or local LLM for editor commands
- Structured state that AI can read/modify
- Command parser for natural language → actions

---

## Phase 1: Local Tool Installation (Week 1)

### 1.1 Install Whisper Locally
```bash
# Create Python virtual environment
python3 -m venv ~/whisper-env
source ~/whisper-env/bin/activate

# Install whisper-timestamped
pip install whisper-timestamped

# Test it works
whisper_timestamped test.mp3 --model base
```

### 1.2 Install Remotion Locally
```bash
# In a new directory for Remotion project
mkdir ~/remotion-renderer && cd ~/remotion-renderer
npx create-video@latest

# Install dependencies
npm install
```

### 1.3 Laravel Services
Create services that call local installations:

**Files to create:**
- `app/Services/WhisperService.php` - Calls Python whisper
- `app/Services/LocalRemotionService.php` - Calls Remotion CLI
- `config/video-tools.php` - Paths configuration

---

## Phase 2: Data Structure & Basic UI (Week 2)

### 2.1 Database Updates

**video_projects table updates:**
```php
// Add columns for editor state
$table->json('timeline_data')->nullable(); // Full timeline state
$table->json('tracks')->nullable(); // Track configuration
$table->string('current_output_size')->default('1080p_16_9');
```

**New table: video_project_assets**
```php
Schema::create('video_project_assets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('video_project_id')->constrained()->cascadeOnDelete();
    $table->string('type'); // audio, image, video, text
    $table->string('name');
    $table->string('file_path')->nullable();
    $table->string('url')->nullable();
    $table->integer('duration_ms')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamps();
});
```

### 2.2 Vue Editor Component Structure
```
resources/js/components/video-editor/
├── VideoEditor.vue           # Main editor container
├── Timeline.vue              # Timeline with tracks
├── Track.vue                 # Individual track
├── Waveform.vue              # Audio waveform (WaveSurfer)
├── PreviewPanel.vue          # Live video preview
├── PropertiesPanel.vue       # Selected item properties
├── Toolbar.vue               # Tools and actions
├── AiCommandBar.vue          # AI command input
└── utils/
    ├── timeline-utils.js     # Timeline calculations
    └── state-manager.js      # Editor state management
```

### 2.3 Basic Filament Page
```php
// app/Filament/Admin/Pages/VideoEditor.php
class VideoEditor extends Page
{
    protected static string $view = 'filament.admin.pages.video-editor';

    public ?int $projectId = null;
    public array $editorState = [];

    // Livewire methods for Vue communication
    public function saveState(array $state): void { }
    public function loadProject(int $id): array { }
    public function runAiCommand(string $command): array { }
    public function detectTimestamps(string $audioPath): array { }
    public function renderVideo(array $options): void { }
}
```

---

## Phase 3: Waveform & Timeline (Week 3)

### 3.1 Audio Waveform
- Load audio file into WaveSurfer.js
- Display waveform with zoom controls
- Click-to-seek functionality
- Regions for lyric segments

### 3.2 Timeline Implementation
- Horizontal scrolling timeline
- Time ruler with zoom levels
- Playhead that syncs with audio
- Multiple tracks:
  - Audio track (waveform)
  - Lyrics track (text blocks)
  - Background track (images/videos)
  - Effects track (transitions, animations)

### 3.3 Drag & Drop
- Drag lyric blocks to adjust timing
- Resize blocks to change duration
- Snap-to-grid option
- Undo/redo support

---

## Phase 4: Live Preview (Week 4)

### 4.1 Preview Panel
- Canvas-based preview showing current frame
- Text rendering with styling
- Background display
- Playback controls (play, pause, seek)

### 4.2 Real-time Updates
- Preview updates as you edit
- Style changes reflect immediately
- Position changes animate smoothly

### 4.3 Output Size Preview
- Toggle between output sizes
- Safe zone guides
- Aspect ratio preview

---

## Phase 5: Styling & Properties (Week 5)

### 5.1 Per-Item Styling
- Font family, size, color
- Text position (9 positions + custom)
- Animation type (fade, slide, karaoke, typewriter)
- Shadow, outline, glow effects
- Background opacity

### 5.2 Section Styling
- Define sections (verse, chorus, bridge)
- Apply styles to entire sections
- Section templates/presets

### 5.3 Global Styles
- Default text style
- Background settings
- Transition defaults

---

## Phase 6: AI Integration (Week 6)

### 6.1 AI Command Bar
- Text input at bottom of editor
- Natural language processing
- Command history
- Suggestions as you type

### 6.2 Supported Commands
```
"Detect timestamps from audio"
"Move all chorus sections 1 second earlier"
"Make verse text blue and chorus gold"
"Add fade animation to all lyrics"
"Split this line into two at the comma"
"Sync line 5 to start at 0:45"
"Apply worship style preset"
"Import lyrics from Suno link [url]"
```

### 6.3 AI Implementation
```php
// app/Services/VideoEditorAiService.php
class VideoEditorAiService
{
    public function parseCommand(string $command, array $editorState): array
    {
        // Use Claude API to understand command
        // Return action object: { action: 'move', target: 'chorus', offset: -1000 }
    }

    public function applyAction(array $action, array $editorState): array
    {
        // Apply action to state
        // Return updated state
    }

    public function getSuggestions(array $editorState): array
    {
        // Analyze state for potential improvements
        // Return suggestions
    }
}
```

---

## Phase 7: Rendering (Week 7-8)

### 7.1 FFmpeg Rendering
- Quick renders for preview
- Simple compositions
- Format conversion

### 7.2 Remotion Rendering
- Complex animations
- Karaoke effects
- Professional output

### 7.3 Batch Rendering
- Queue multiple output sizes
- Progress tracking
- Notification on completion

### 7.4 Output Management
- Save to Media Library
- Direct download
- Share links

---

## UI Design Concepts

### Color Palette (Modern/Bright)
```css
--bg-primary: #0f0f0f;      /* Dark background */
--bg-secondary: #1a1a2e;    /* Panels */
--bg-track: #16213e;        /* Track backgrounds */
--accent-primary: #7c3aed;  /* Purple - primary actions */
--accent-secondary: #06b6d4; /* Cyan - secondary */
--accent-success: #10b981;  /* Green - success */
--accent-warning: #f59e0b;  /* Orange - warnings */
--text-primary: #ffffff;
--text-secondary: #94a3b8;
```

### Layout
```
┌─────────────────────────────────────────────────────────────┐
│  Toolbar (File, Edit, View, AI Commands)                    │
├───────────────────────────────────┬─────────────────────────┤
│                                   │                         │
│         Preview Panel             │    Properties Panel     │
│         (Live Preview)            │    (Selected Item)      │
│                                   │                         │
├───────────────────────────────────┴─────────────────────────┤
│  Timeline                                                    │
│  ├─ Audio Track [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓]    │
│  ├─ Lyrics Track [Verse 1][   ][Chorus][   ][Verse 2]       │
│  ├─ Background  [████████████████████████████████████]       │
│  └─ Effects     [fade]        [fade]        [fade]          │
├─────────────────────────────────────────────────────────────┤
│  AI Command Bar: "Type a command or ask AI for help..."     │
└─────────────────────────────────────────────────────────────┘
```

---

## File Structure

```
app/
├── Filament/Admin/Pages/
│   └── VideoEditor.php
├── Services/
│   ├── WhisperService.php
│   ├── LocalRemotionService.php
│   └── VideoEditorAiService.php
├── Jobs/
│   ├── DetectTimestampsJob.php
│   └── RenderVideoJob.php
└── Models/
    ├── VideoProject.php (updated)
    └── VideoProjectAsset.php (new)

resources/
├── js/
│   └── components/
│       └── video-editor/
│           ├── VideoEditor.vue
│           ├── Timeline.vue
│           ├── Waveform.vue
│           ├── PreviewPanel.vue
│           └── ...
└── views/filament/admin/pages/
    └── video-editor.blade.php

config/
└── video-tools.php
```

---

## Getting Started (Immediate Next Steps)

1. **Install Whisper locally** - Test timestamp detection
2. **Install Remotion locally** - Test video rendering
3. **Create Laravel services** - WhisperService, LocalRemotionService
4. **Set up Vue in Filament** - Configure Vite for Vue components
5. **Build basic Timeline component** - Start with simple drag-drop
6. **Integrate WaveSurfer.js** - Display audio waveform

---

## Questions to Resolve

1. Should the editor be a **separate full-page app** or **embedded in Filament panel**?
2. Do you want **auto-save** as you edit, or manual save?
3. Should AI commands go through **Claude API** or use a **local LLM**?
4. Priority: **Whisper timestamps first** or **basic editor UI first**?

---

*This is an ambitious but achievable project. We'll build it incrementally, getting something working at each phase.*
