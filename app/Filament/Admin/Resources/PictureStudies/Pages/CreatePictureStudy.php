<?php

namespace App\Filament\Admin\Resources\PictureStudies\Pages;

use App\Filament\Admin\Resources\PictureStudies\PictureStudyResource;
use App\Helpers\SocialShareHelper;
use App\Jobs\PostToFacebookJob;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreatePictureStudy extends CreateRecord
{
    protected static string $resource = PictureStudyResource::class;

    protected bool $publishToFacebook = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        $this->publishToFacebook = $data['publish_to_facebook'] ?? false;
        unset($data['publish_to_facebook']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($this->publishToFacebook && $record->image_path) {
            $imageUrl = Storage::disk('public')->url($record->image_path);
            $caption = SocialShareHelper::generateCaption(
                $record->title,
                $record->description,
                route('resources.picture-studies'),
                '📖 See all picture studies at'
            );

            PostToFacebookJob::dispatch($record, $imageUrl, $caption);

            Notification::make()
                ->title('Posting to Facebook...')
                ->body('Your picture study is being posted to the Facebook Page.')
                ->info()
                ->send();
        }

        $whatsappUrl = SocialShareHelper::whatsappShareUrl(
            "📖 New Picture Study: {$record->title}",
            route('picture-study.download', $record)
        );

        Notification::make()
            ->title('Picture Study Created!')
            ->success()
            ->actions([
                Action::make('share_whatsapp')
                    ->label('Share to WhatsApp')
                    ->icon('heroicon-o-share')
                    ->url($whatsappUrl, shouldOpenInNewTab: true),
            ])
            ->persistent()
            ->send();
    }
}
