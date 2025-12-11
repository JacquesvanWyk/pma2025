<?php

namespace App\Filament\Admin\Pages;

use App\Models\LyricTimestamp;
use App\Models\VideoProject;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class VideoEditor extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-film';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?string $navigationLabel = 'Video Editor';

    protected static ?string $title = 'Video Editor';

    protected static ?int $navigationSort = 8;

    protected string $view = 'filament.admin.pages.video-editor';

    public ?string $audioUrl = null;

    public array $lyrics = [];

    public ?int $projectId = null;

    public ?VideoProject $project = null;

    public function mount(): void
    {
        $projectIdParam = request()->query('project');

        if ($projectIdParam) {
            $this->project = VideoProject::where('id', $projectIdParam)
                ->where('user_id', auth()->id())
                ->with(['lyricTimestamps', 'exports'])
                ->first();

            if ($this->project) {
                $this->projectId = $this->project->id;
                $this->audioUrl = $this->project->audio_source_url;
                $this->lyrics = $this->project->lyricTimestamps->map(fn ($lyric) => [
                    'order' => $lyric->order,
                    'text' => $lyric->text,
                    'start_ms' => $lyric->start_ms,
                    'end_ms' => $lyric->end_ms,
                    'animation' => $lyric->animation,
                ])->toArray();
            }
        } else {
            $this->audioUrl = request()->query('audio_url', '');
            $this->lyrics = json_decode(request()->query('lyrics', '[]'), true) ?? [];
        }
    }

    public function getProjectDataJson(): string
    {
        if (! $this->project) {
            return 'null';
        }

        return json_encode([
            'id' => $this->project->id,
            'name' => $this->project->name,
            'audio_url' => $this->project->audio_source_url,
            'audio_duration_ms' => $this->project->audio_duration_ms,
            'background_type' => $this->project->background_type ?? 'color',
            'background_color' => $this->project->background_type === 'color' ? ($this->project->background_value ?? '#000000') : '#000000',
            'background_image' => $this->project->background_type === 'image' ? $this->project->background_value : null,
            'background_video' => $this->project->background_type === 'video' ? $this->project->background_value : null,
            'logo_url' => $this->project->logo_url,
            'logo_position' => $this->project->logo_position ?? 'bottom-right',
            'text_style' => $this->project->text_style ?? [],
            'settings' => $this->project->settings ?? [],
            'resolution' => $this->project->resolution ?? '1920x1080',
            'reference_lyrics' => $this->project->reference_lyrics,
            'lyrics' => $this->lyrics,
            'exports' => $this->project->exports->map(fn ($export) => [
                'id' => $export->id,
                'resolution' => $export->resolution,
                'file_url' => $export->file_url,
                'file_size_bytes' => $export->file_size_bytes,
                'formatted_file_size' => $export->formatted_file_size,
                'created_at' => $export->created_at->toISOString(),
            ])->toArray(),
        ]);
    }

    public function getLyricsJson(): string
    {
        return json_encode($this->lyrics);
    }

    #[On('save-project')]
    public function saveProject(array $data): array
    {
        try {
            return DB::transaction(function () use ($data) {
                $projectId = $data['project_id'] ?? null;
                $backgroundType = $data['background_type'] ?? 'color';

                $updateData = [
                    'name' => $data['name'] ?? 'Untitled Project',
                    'audio_url' => $data['audio_url'] ?? null,
                    'audio_duration_ms' => $data['audio_duration_ms'] ?? 0,
                    'background_type' => $backgroundType,
                    'background_value' => $backgroundType === 'color'
                        ? ($data['background_color'] ?? '#000000')
                        : ($data['background_image'] ?? null),
                    'logo_position' => $data['logo_position'] ?? 'bottom-right',
                    'text_style' => $data['text_style'] ?? [],
                    'settings' => $data['settings'] ?? [],
                    'resolution' => $data['resolution'] ?? '1920x1080',
                ];

                if ($projectId) {
                    $project = VideoProject::where('id', $projectId)
                        ->where('user_id', auth()->id())
                        ->first();

                    if (! $project) {
                        return ['success' => false, 'error' => 'Project not found'];
                    }

                    $project->update($updateData);

                    if (isset($data['lyrics'])) {
                        $project->lyricTimestamps()->delete();
                        $this->saveLyrics($project, $data['lyrics']);
                    }
                } else {
                    $project = VideoProject::create(array_merge($updateData, [
                        'user_id' => auth()->id(),
                        'type' => 'lyric_video',
                        'status' => 'draft',
                    ]));

                    if (isset($data['lyrics'])) {
                        $this->saveLyrics($project, $data['lyrics']);
                    }
                }

                $this->projectId = $project->id;

                return [
                    'success' => true,
                    'project' => [
                        'id' => $project->id,
                        'name' => $project->name,
                    ],
                ];
            });
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function saveLyrics(VideoProject $project, array $lyrics): void
    {
        foreach ($lyrics as $index => $lyric) {
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

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
