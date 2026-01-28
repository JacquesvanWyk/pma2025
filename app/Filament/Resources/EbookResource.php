<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class EbookResource extends Resource
{
    protected static ?string $model = Ebook::class;

    protected static ?string $navigationIcon = 'heroicon-o-book';

    public static function form(Forms\Form $form): void
    {
        $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('author')
                    ->label('Author')
                    ->maxLength(255),
                Forms\Components\TextInput::make('edition')
                    ->label('Edition')
                    ->maxLength(255),
                Forms\Components\Select::make('language')
                    ->label('Language')
                    ->options([
                        'English' => 'English',
                        'Afrikaans' => 'Afrikaans',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
                Forms\Components\TextInput::make('pdf_file')
                    ->label('PDF File')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('thumbnail')
                    ->label('Thumbnail')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_featured')
                    ->label('Featured')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table
            ->columns([
                Tables\Columns\Text::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\Text::make('author')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\Text::make('language')
                    ->label('Language')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\Text::make('edition')
                    ->label('Edition')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('download_count')
                    ->label('Downloads')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('language')
                    ->label('Language')
                    ->options([
                        'English' => 'English',
                        'Afrikaans' => 'Afrikaans',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->label('Category')
                    ->options([
                        'Doctrine' => 'Doctrine',
                        'Gospel' => 'Gospel',
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All')
                    ->default(null)
                    ->trueLabel('Featured')
                    ->falseLabel('Not Featured')
                    ->queries(
                        fn (Builder $query): Builder => $query->where('is_featured', true),
                        fn (Builder $query): Builder => $query->where('is_featured', false),
                    ),
            ])
            ->actions([
                Tables\Actions\CreateAction::make()
                    ->label('Add E-book'),
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Delete'),
            ]);
    }
}
