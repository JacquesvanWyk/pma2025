<?php

namespace App\Filament\Admin\Resources\VideoProjects\Pages;

use App\Filament\Admin\Resources\VideoProjects\VideoProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVideoProject extends CreateRecord
{
    protected static string $resource = VideoProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['type'] = 'lyric_video';
        $data['status'] = 'draft';

        $backgroundType = $data['background_type'] ?? 'color';

        $data['background_value'] = match ($backgroundType) {
            'color' => $data['background_color'] ?? '#000000',
            'image' => $data['background_image_file'] ?? null,
            'video' => $data['background_video_file'] ?? null,
            default => '#000000',
        };

        unset($data['background_color'], $data['background_image_file'], $data['background_video_file']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.pages.video-editor', ['project' => $this->record->id]);
    }
}
