<?php

namespace App\Filament\Admin\Resources\Galleries\RelationManagers;

use App\Models\GalleryImage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Gallery Images';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->image()
                    ->imageEditor()
                    ->directory('galleries/images')
                    ->visibility('public')
                    ->maxSize(5120)
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('title')
                    ->maxLength(255),

                Forms\Components\Textarea::make('caption')
                    ->rows(2)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('alt_text')
                    ->label('Alt Text')
                    ->helperText('For accessibility')
                    ->maxLength(255),

                Forms\Components\TextInput::make('order_position')
                    ->label('Order')
                    ->numeric()
                    ->default(0),

                Forms\Components\Select::make('taggedUsers')
                    ->label('Tag People in Photo')
                    ->relationship('taggedUsers', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->helperText('Tag registered users who appear in this photo')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->reorderable('order_position')
            ->columns([
                ImageColumn::make('image_path')
                    ->disk('public')
                    ->label('Image')
                    ->square()
                    ->size(80),

                TextColumn::make('title')
                    ->searchable()
                    ->placeholder('Untitled'),

                TextColumn::make('taggedUsers.name')
                    ->label('Tagged')
                    ->badge()
                    ->separator(', ')
                    ->limitList(3)
                    ->placeholder('—'),

                TextColumn::make('download_count')
                    ->label('Downloads')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('bulkUpload')
                    ->label('Bulk Upload')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->form([
                        Forms\Components\FileUpload::make('images')
                            ->label('Select Images')
                            ->disk('public')
                            ->image()
                            ->directory('galleries/images')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(50)
                            ->required()
                            ->helperText('Select up to 50 images at once'),
                    ])
                    ->action(function (array $data): void {
                        $gallery = $this->getOwnerRecord();
                        $maxOrder = $gallery->images()->max('order_position') ?? 0;

                        foreach ($data['images'] as $index => $imagePath) {
                            GalleryImage::create([
                                'gallery_id' => $gallery->id,
                                'image_path' => $imagePath,
                                'order_position' => $maxOrder + $index + 1,
                                'created_by' => auth()->id(),
                            ]);
                        }

                        Notification::make()
                            ->title(count($data['images']).' images uploaded successfully')
                            ->success()
                            ->send();
                    }),

                CreateAction::make()
                    ->label('Add Single Image')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = auth()->id();

                        return $data;
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order_position');
    }
}
