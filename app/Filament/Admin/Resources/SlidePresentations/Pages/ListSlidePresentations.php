<?php

namespace App\Filament\Admin\Resources\SlidePresentations\Pages;

use App\Filament\Admin\Resources\SlidePresentations\SlidePresentationResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListSlidePresentations extends ListRecords
{
    protected static string $resource = SlidePresentationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('New Presentation')
                ->icon('heroicon-o-plus')
                ->url(fn () => route('filament.admin.pages.slide-generator')),
        ];
    }
}
