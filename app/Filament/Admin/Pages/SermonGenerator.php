<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\Sermon;
use App\Models\SermonSeries;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;

/**
 * @property-read Schema $form
 */
class SermonGenerator extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-megaphone';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?string $navigationLabel = 'Sermon Generator';

    protected static ?string $title = 'AI Sermon Generator';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.admin.pages.sermon-generator';

    public ?array $data = [];

    public ?string $generatedContent = null;

    public bool $isGenerating = false;

    public ?string $generationStatusMessage = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function checkGenerationStatus(): void
    {
        if (! $this->isGenerating) {
            return;
        }

        $componentId = $this->getId();

        // Check for completion
        $complete = \Illuminate\Support\Facades\Cache::get("sermon-generation-{$componentId}-complete");
        if ($complete) {
            $this->generatedContent = $complete['content'];
            $this->isGenerating = false;
            $this->generationStatusMessage = null;

            \Illuminate\Support\Facades\Cache::forget("sermon-generation-{$componentId}-complete");
            \Illuminate\Support\Facades\Cache::forget("sermon-generation-{$componentId}-status");
            \Illuminate\Support\Facades\Cache::forget("sermon-generation-{$componentId}-content");

            return;
        }

        // Check for error
        $error = \Illuminate\Support\Facades\Cache::get("sermon-generation-{$componentId}-error");
        if ($error) {
            $this->isGenerating = false;
            $this->generationStatusMessage = null;

            \Illuminate\Support\Facades\Cache::forget("sermon-generation-{$componentId}-error");

            Notification::make()
                ->title('Generation Failed')
                ->danger()
                ->body($error)
                ->send();

            return;
        }

        // Check for status update
        $status = \Illuminate\Support\Facades\Cache::get("sermon-generation-{$componentId}-status");
        if ($status) {
            $this->generationStatusMessage = $status;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('saveAsSermon')
                ->label('Save as Sermon')
                ->icon('heroicon-o-bookmark')
                ->color('success')
                ->visible(fn () => $this->generatedContent !== null)
                ->form([
                    TextInput::make('title')
                        ->required()
                        ->default(fn () => $this->data['title'] ?? '')
                        ->columnSpanFull(),

                    TextInput::make('subtitle')
                        ->columnSpanFull(),

                    Select::make('series_id')
                        ->label('Sermon Series')
                        ->options(SermonSeries::pluck('title', 'id'))
                        ->searchable()
                        ->preload(),

                    Select::make('language')
                        ->required()
                        ->options([
                            'english' => 'English',
                            'afrikaans' => 'Afrikaans',
                        ])
                        ->default('english'),

                    Select::make('status')
                        ->required()
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                        ])
                        ->default('draft'),
                ])
                ->action(function (array $data) {
                    $this->saveAsSermon($data);
                }),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    TextInput::make('title')
                        ->label('Sermon Title')
                        ->required()
                        ->placeholder('e.g., The Grace of God, Stepping into Faith')
                        ->helperText('Enter the main title for your sermon')
                        ->columnSpanFull(),

                    TextInput::make('primary_scripture')
                        ->label('Primary Scripture')
                        ->required()
                        ->placeholder('e.g., John 3:16, Romans 8:28-39')
                        ->helperText('Main Bible passage(s) for this sermon')
                        ->columnSpanFull(),

                    Select::make('sermon_type')
                        ->label('Sermon Type')
                        ->required()
                        ->options([
                            'evangelistic' => 'Evangelistic - Reaching the Lost',
                            'doctrinal' => 'Doctrinal - Teaching Present Truth',
                            'expository' => 'Expository - Verse by Verse',
                            'topical' => 'Topical - Theme-Based',
                            'devotional' => 'Devotional - Personal Growth',
                        ])
                        ->default('doctrinal')
                        ->helperText('Choose the type of sermon'),

                    Select::make('target_audience')
                        ->label('Target Audience')
                        ->required()
                        ->options([
                            'general' => 'General Congregation',
                            'youth' => 'Youth & Young Adults',
                            'new_believers' => 'New Believers',
                            'seekers' => 'Seekers & Non-Believers',
                        ])
                        ->default('general'),

                    Select::make('duration')
                        ->label('Sermon Duration')
                        ->required()
                        ->options([
                            '15' => '15 minutes',
                            '30' => '30 minutes',
                            '45' => '45 minutes',
                            '60' => '60 minutes',
                        ])
                        ->default('45')
                        ->helperText('Target sermon length'),

                    Textarea::make('key_points')
                        ->label('Key Points to Cover (Optional)')
                        ->rows(4)
                        ->placeholder('- Point 1: The nature of God\'s grace&#10;- Point 2: How to receive grace&#10;- Point 3: Living in grace')
                        ->helperText('List the main points you want covered in the sermon')
                        ->columnSpanFull(),

                    Textarea::make('additional_notes')
                        ->label('Additional Instructions (Optional)')
                        ->rows(3)
                        ->placeholder('Any specific emphasis, illustrations, or applications...')
                        ->columnSpanFull(),
                ])
                    ->livewireSubmitHandler('generateSermon')
                    ->key('form-actions')
                    ->footer([
                        Actions::make($this->getFormActions()),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('generate')
                ->label('Generate Sermon')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->size('lg')
                ->requiresConfirmation()
                ->modalHeading('Generate AI Sermon?')
                ->modalDescription('This will use AI to create a sermon based on your inputs and Adventist theology. Please review the content for theological accuracy.')
                ->modalSubmitActionLabel('Generate')
                ->action(function () {
                    $this->generateSermon();
                })
                ->disabled(fn () => $this->isGenerating),

            Action::make('clear')
                ->label('Clear')
                ->color('gray')
                ->visible(fn () => $this->generatedContent !== null)
                ->action(function () {
                    $this->generatedContent = null;
                    Notification::make()
                        ->title('Content cleared')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function generateSermon(): void
    {
        $this->validate();

        $data = $this->form->getState();

        $this->isGenerating = true;
        $this->generatedContent = null;
        $this->generationStatusMessage = 'Queuing generation job...';

        try {
            $job = new \App\Jobs\GenerateSermonJob(
                formData: $data,
                componentId: $this->getId()
            );

            dispatch($job);

            $this->generationStatusMessage = 'Generation started...';

            Notification::make()
                ->title('Generation Started')
                ->info()
                ->body('Your sermon is being generated. Please wait...')
                ->send();
        } catch (\Exception $e) {
            $this->isGenerating = false;

            Notification::make()
                ->title('Failed to Start Generation')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        }
    }

    public function downloadSermon(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $data = $this->form->getState();
        $title = $data['title'] ?? 'sermon';
        $filename = \Illuminate\Support\Str::slug($title).'-'.now()->format('Y-m-d').'.md';

        return response()->streamDownload(function () {
            echo $this->generatedContent;
        }, $filename, [
            'Content-Type' => 'text/markdown',
        ]);
    }

    public function saveAsSermon(array $data): void
    {
        $content = $this->generatedContent;

        if (! $content) {
            Notification::make()
                ->title('No Content')
                ->danger()
                ->body('There is no content to save. Please generate a sermon first.')
                ->send();

            return;
        }

        $formData = $this->form->getState();

        $sermon = Sermon::create([
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'series_id' => $data['series_id'] ?? null,
            'content' => $content,
            'excerpt' => \Illuminate\Support\Str::limit(strip_tags($content), 200),
            'primary_scripture' => $formData['primary_scripture'] ?? null,
            'language' => $data['language'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? now() : null,
            'duration_minutes' => (int) ($formData['duration'] ?? 45),
        ]);

        Notification::make()
            ->title('Sermon Saved Successfully!')
            ->success()
            ->body("The sermon \"{$sermon->title}\" has been saved.")
            ->send();

        $this->redirect(route('filament.admin.resources.sermons.edit', ['record' => $sermon]));

        // Clear the generated content after saving
        $this->generatedContent = null;
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
