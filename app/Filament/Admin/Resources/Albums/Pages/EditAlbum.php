<?php

namespace App\Filament\Admin\Resources\Albums\Pages;

use App\Filament\Admin\Resources\Albums\AlbumResource;
use App\Jobs\FixSongMetadata;
use App\Jobs\GenerateAlbumDownloads;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAlbum extends EditRecord
{
    protected static string $resource = AlbumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('regenerateDownloads')
                ->label('Regenerate Downloads')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Regenerate Album Downloads')
                ->modalDescription('This will regenerate all download bundles (audio, video, full) for this album. This may take a few minutes.')
                ->modalSubmitActionLabel('Regenerate')
                ->action(function () {
                    $album = $this->record;

                    // First fix all song metadata
                    foreach ($album->songs as $song) {
                        if ($song->wav_file) {
                            FixSongMetadata::dispatch($song);
                        }
                    }

                    // Then regenerate album downloads after metadata is fixed
                    GenerateAlbumDownloads::dispatch($album)->delay(now()->addSeconds(30));

                    Notification::make()
                        ->title('Download regeneration queued')
                        ->body('The album downloads will be regenerated shortly. This may take a few minutes.')
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
