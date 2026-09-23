<?php

namespace App\Filament\Resources\BusinessHours\Schemas;

use App\Models\BusinessHour;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BusinessHourForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Schedule')
                    ->schema([
                        Select::make('kind')->options([
                            BusinessHour::KIND_WEEKLY => 'Weekly schedule',
                            BusinessHour::KIND_EXCEPTION => 'Date exception',
                        ])->live()->required(),
                        Select::make('day_of_week')->options([
                            0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                            4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday',
                        ])->required(fn (Get $get): bool => $get('kind') === BusinessHour::KIND_WEEKLY)
                            ->visible(fn (Get $get): bool => $get('kind') === BusinessHour::KIND_WEEKLY),
                        DatePicker::make('date')
                            ->required(fn (Get $get): bool => $get('kind') === BusinessHour::KIND_EXCEPTION)
                            ->visible(fn (Get $get): bool => $get('kind') === BusinessHour::KIND_EXCEPTION),
                        TextInput::make('label')->maxLength(160),
                        Toggle::make('is_closed')->label('Closed')->live(),
                        Grid::make(4)->schema([
                            TimePicker::make('open_time')->required(fn (Get $get): bool => ! $get('is_closed')),
                            TimePicker::make('close_time')->required(fn (Get $get): bool => ! $get('is_closed')),
                            TimePicker::make('second_open_time'),
                            TimePicker::make('second_close_time'),
                        ]),
                    ])->columnSpanFull(),
            ]);
    }
}
