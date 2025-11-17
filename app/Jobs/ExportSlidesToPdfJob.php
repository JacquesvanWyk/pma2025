<?php

namespace App\Jobs;

use App\Models\Sermon;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportSlidesToPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;

    public function __construct(
        public Sermon $sermon
    ) {}

    public function handle(): void
    {
        try {
            if ($this->sermon->slides->isEmpty()) {
                Notification::make()
                    ->title('No Slides to Export')
                    ->warning()
                    ->body('This sermon has no slides to export.')
                    ->send();

                return;
            }

            // Generate HTML for all slides
            $html = view('slides.pdf-export', [
                'sermon' => $this->sermon,
                'slides' => $this->sermon->slides,
            ])->render();

            // Generate PDF
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                    'isFontSubsettingEnabled' => true,
                ]);

            // Save PDF
            $filename = \Illuminate\Support\Str::slug($this->sermon->title).'-slides-'.now()->format('Y-m-d-His').'.pdf';
            $path = config('slides.export.storage_path').'/'.$filename;

            Storage::disk('local')->makeDirectory(config('slides.export.storage_path'));
            Storage::disk('local')->put($path, $pdf->output());

            // Create download URL
            $downloadUrl = Storage::disk('local')->url($path);

            Notification::make()
                ->title('PDF Export Complete')
                ->success()
                ->body("Your PDF has been generated: {$filename}")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('download')
                        ->label('Download')
                        ->url($downloadUrl)
                        ->openUrlInNewTab(),
                ])
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('PDF Export Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();

            throw $e;
        }
    }
}
