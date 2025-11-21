<?php

namespace App\Filament\Admin\Resources\FeedPosts\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'reactions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('type')
                    ->options([
                        'like' => '👍 Like',
                        'pray' => '🙏 Pray',
                        'amen' => '✝️ Amen',
                        'heart' => '❤️ Love',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'like' => '👍 Like',
                        'pray' => '🙏 Pray',
                        'amen' => '✝️ Amen',
                        'heart' => '❤️ Love',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'like',
                        'success' => 'pray',
                        'warning' => 'amen',
                        'danger' => 'heart',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'like' => '👍 Like',
                        'pray' => '🙏 Pray',
                        'amen' => '✝️ Amen',
                        'heart' => '❤️ Love',
                    ]),
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
