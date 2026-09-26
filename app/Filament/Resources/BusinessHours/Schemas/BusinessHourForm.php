<?php

namespace App\Filament\Resources\BusinessHours\Schemas;

use App\Models\BusinessHour;
use Closure;
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
                        ])->helperText('Date exceptions override the regular weekly schedule on the selected date.')->live()->required(),
                        Select::make('day_of_week')->options([
                            0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                            4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday',
                        ])->required(fn (Get $get): bool => $get('kind') === BusinessHour::KIND_WEEKLY)
                            ->visible(fn (Get $get): bool => $get('kind') === BusinessHour::KIND_WEEKLY),
                        DatePicker::make('date')
                            ->required(fn (Get $get): bool => $get('kind') === BusinessHour::KIND_EXCEPTION)
                            ->visible(fn (Get $get): bool => $get('kind') === BusinessHour::KIND_EXCEPTION),
                        DatePicker::make('end_date')
                            ->label('End date')
                            ->helperText('Leave blank for a single date. A range uses the same hours or closure for every date in between.')
                            ->afterOrEqual('date')
                            ->visible(fn (Get $get): bool => $get('kind') === BusinessHour::KIND_EXCEPTION),
                        Select::make('recurrence')
                            ->label('Repeats')
                            ->options([
                                BusinessHour::RECURRENCE_ONCE => 'One time',
                                BusinessHour::RECURRENCE_YEARLY => 'Every year',
                            ])
                            ->default(BusinessHour::RECURRENCE_ONCE)
                            ->helperText('Annual exceptions use the month and day every year, as supported by Spatie.')
                            ->required(fn (Get $get): bool => $get('kind') === BusinessHour::KIND_EXCEPTION)
                            ->visible(fn (Get $get): bool => $get('kind') === BusinessHour::KIND_EXCEPTION)
                            ->live(),
                        TextInput::make('label')->label('Description')->helperText('Optional context shown in the public Exceptions list.')->maxLength(160),
                        Toggle::make('is_closed')
                            ->label('Closed')
                            ->helperText('Use for a full-day closure, including excluded dates.')
                            ->default(false)
                            ->live(),
                        Grid::make(4)
                            ->visible(fn (Get $get): bool => ! $get->boolean('is_closed'))
                            ->schema([
                                TimePicker::make('open_time')->label('Opens')->required(fn (Get $get): bool => ! $get->boolean('is_closed')),
                                TimePicker::make('close_time')
                                    ->label('Closes')
                                    ->required(fn (Get $get): bool => ! $get->boolean('is_closed'))
                                    ->rules([
                                        fn (Get $get, ?BusinessHour $record): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get, $record): void {
                                            $message = BusinessHour::packageValidationMessage([
                                                'kind' => $get('kind'),
                                                'day_of_week' => $get('day_of_week'),
                                                'date' => $get('date'),
                                                'end_date' => $get('end_date'),
                                                'recurrence' => $get('recurrence'),
                                                'is_closed' => $get('is_closed'),
                                                'open_time' => $get('open_time'),
                                                'close_time' => $value,
                                                'second_open_time' => $get('second_open_time'),
                                                'second_close_time' => $get('second_close_time'),
                                            ], $record);

                                            if ($message !== null) {
                                                $fail($message);
                                            }
                                        },
                                    ]),
                                TimePicker::make('second_open_time')
                                    ->label('Second opening')
                                    ->required(fn (Get $get): bool => ! $get->boolean('is_closed') && filled($get('second_close_time'))),
                                TimePicker::make('second_close_time')
                                    ->label('Second closing')
                                    ->required(fn (Get $get): bool => ! $get->boolean('is_closed') && filled($get('second_open_time'))),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
}
