<?php

namespace App\Filament\Admin\Resources\VideoProjects\Pages;

use App\Filament\Admin\Resources\VideoProjects\VideoProjectResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditVideoProject extends EditRecord
{
    protected static string $resource = VideoProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('open_editor')
                ->label('Open Editor')
                ->icon(Heroicon::OutlinedPlay)
                ->color('success')
                ->url(fn () => route('filament.admin.pages.video-editor', ['project' => $this->record->id])),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
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
}
