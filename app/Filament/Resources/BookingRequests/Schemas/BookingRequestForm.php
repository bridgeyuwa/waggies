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
                            Select::make('status')
                                ->options(BookingRequestStatus::options())
                                ->required(),
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
                            TextInput::make('service_variant')->label('Variant')->disabled()->dehydrated(false),
                            TextInput::make('pricing_tier')->label('Pricing tier')->disabled()->dehydrated(false),
                            TextInput::make('source')->label('Source')->disabled()->dehydrated(false),
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
                            TextInput::make('quote_amount')->label('Quote amount')->numeric()->integer()->minValue(0),
                            TextInput::make('quote_currency')->label('Currency')->default('NGN')->maxLength(3),
                            Textarea::make('quote_notes')->label('Quote notes')->rows(3)->columnSpanFull(),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
