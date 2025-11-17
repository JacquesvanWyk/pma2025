<?php

namespace App\Filament\Admin\Resources\PledgeProgress\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PledgeProgressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('current_amount')
                    ->label('Current Pledge Amount')
                    ->required()
                    ->numeric()
                    ->prefix('R')
                    ->step(0.01)
                    ->helperText('Enter the current total pledged amount for this month')
                    ->default(0.0),
                Select::make('month')
                    ->label('Pledge Month')
                    ->required()
                    ->options([
                        'January' => 'January',
                        'February' => 'February',
                        'March' => 'March',
                        'April' => 'April',
                        'May' => 'May',
                        'June' => 'June',
                        'July' => 'July',
                        'August' => 'August',
                        'September' => 'September',
                        'October' => 'October',
                        'November' => 'November',
                        'December' => 'December',
                    ])
                    ->helperText('Select the month for this pledge period'),
                TextInput::make('goal_amount')
                    ->label('Monthly Goal')
                    ->required()
                    ->numeric()
                    ->prefix('R')
                    ->step(0.01)
                    ->helperText('Monthly pledge goal (default: R35,000)')
                    ->default(35000.0),
            ]);
    }
}
