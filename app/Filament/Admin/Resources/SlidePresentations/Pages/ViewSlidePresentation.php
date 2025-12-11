<?php

namespace App\Filament\Admin\Resources\SlidePresentations\Pages;

use App\Filament\Admin\Resources\SlidePresentations\SlidePresentationResource;
use App\Jobs\RegenerateSlideJob;
use App\Services\SlideLogoService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;

class ViewSlidePresentation extends ViewRecord
{
    protected static string $resource = SlidePresentationResource::class;

    protected string $view = 'filament.admin.resources.slide-presentations.view';

    public bool $isRegenerating = false;

    public ?int $regeneratingSlide = null;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('continueEditing')
                ->label('Edit in Generator')
                ->icon('heroicon-o-pencil-square')
                ->url(fn () => route('filament.admin.pages.slide-generator', ['presentation' => $this->record->id])),
            Action::make('downloadAll')
                ->label('Download All')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => $this->downloadAllSlides()),
            DeleteAction::make(),
        ];
    }

    public function regenerateSlide(int $index): void
    {
        $slides = $this->record->slides ?? [];

        if (! isset($slides[$index])) {
            Notification::make()
                ->title('Slide not found')
                ->danger()
                ->send();

            return;
        }

        $this->isRegenerating = true;
        $this->regeneratingSlide = $index;

        dispatch(new RegenerateSlideJob($this->record->id, $index));

        Notification::make()
            ->title('Regenerating Slide')
            ->info()
            ->body('Slide '.($index + 1).' is being regenerated. Refresh to see the result.')
            ->send();
    }

    public function downloadSlide(int $index, bool $withLogo = false): void
    {
        $slides = $this->record->slides ?? [];

        if (! isset($slides[$index]) || ! $slides[$index]['image_path']) {
            Notification::make()
                ->title('Slide not available')
                ->warning()
                ->send();

            return;
        }

        $path = $slides[$index]['image_path'];

        if ($withLogo) {
            $logoService = new SlideLogoService;
            $downloadPath = $logoService->addLogoToSlide($path);

            if ($downloadPath) {
                $this->redirect(asset('storage/'.$downloadPath));

                return;
            }
        }

        $this->redirect(asset('storage/'.$path));
    }

    public function downloadAllSlides(): void
    {
        Notification::make()
            ->title('Download Started')
            ->info()
            ->body('Opening slides in new tabs...')
            ->send();
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
