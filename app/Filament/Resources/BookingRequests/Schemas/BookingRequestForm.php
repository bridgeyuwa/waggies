<?php

namespace App\Filament\Resources\BookingRequests\Schemas;

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')->label('Name')->disabled()->dehydrated(false),
                            TextInput::make('phone')->label('Phone')->disabled()->dehydrated(false),
                            TextInput::make('email')->label('Email')->email()->disabled()->dehydrated(false),
                            TextInput::make('preferred_contact_method')->label('Preferred contact')->disabled()->dehydrated(false),
                        ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Request')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('service_key')
                                ->label('Service')
                                ->options(BookingRequest::serviceOptions())
                                ->disabled()
                                ->dehydrated(false),
                            TextInput::make('status')
                                ->label('Request status')
                                ->formatStateUsing(fn (?string $state): string => BookingRequestStatus::options()[$state] ?? (string) $state)
                                ->disabled()
                                ->dehydrated(false),
                            DatePicker::make('requested_date')
                                ->label('Requested date')
                                ->disabled()
                                ->dehydrated(false),
                            TimePicker::make('requested_time')
                                ->label('Requested time')
                                ->disabled()
                                ->dehydrated(false),
                            TextInput::make('pet_name')->label('Pet name')->disabled()->dehydrated(false),
                            TextInput::make('pet_type')->label('Pet type')->disabled()->dehydrated(false),
                            TextInput::make('location')->label('Location')->disabled()->dehydrated(false)->columnSpanFull(),
                            TextInput::make('service_variant')
                                ->label('Primary service option')
                                ->helperText('Multi-option details are shown in the Services section below.')
                                ->disabled()
                                ->dehydrated(false),
                            TextInput::make('source')->label('Source')->disabled()->dehydrated(false),
                            Textarea::make('context')
                                ->label('Submission context')
                                ->formatStateUsing(fn (?array $state): string => $state === [] || $state === null
                                    ? 'Not provided'
                                    : (json_encode($state, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: 'Not provided'))
                                ->disabled()
                                ->dehydrated(false)
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Notes')
                    ->schema([
                        Textarea::make('message')
                            ->label('Customer message')
                            ->rows(6)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                        Textarea::make('internal_notes')
                            ->label('Internal notes')
                            ->rows(5)
                            ->helperText('Never shown on public pages or in customer messages.')
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            TextInput::make('quote_amount')
                                ->label('Calculated quote total')
                                ->numeric()
                                ->integer()
                                ->disabled()
                                ->dehydrated(false)
                                ->helperText('Calculated from the service quotes below.'),
                            TextInput::make('quote_currency')
                                ->label('Quote currency')
                                ->disabled()
                                ->dehydrated(false),
                            Textarea::make('quote_notes')
                                ->label('Calculated quote notes')
                                ->rows(3)
                                ->disabled()
                                ->dehydrated(false)
                                ->helperText('Service-level quote notes are the source of truth.')
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
