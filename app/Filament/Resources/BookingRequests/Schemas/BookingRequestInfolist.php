<?php

namespace App\Filament\Resources\BookingRequests\Schemas;

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class BookingRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                        ])->schema([
                            TextEntry::make('name')
                                ->label('Name'),
                            TextEntry::make('email')
                                ->label('Email')
                                ->copyable(),
                            TextEntry::make('phone')
                                ->label('Phone')
                                ->copyable(),
                            TextEntry::make('preferred_contact_method')
                                ->label('Preferred contact')
                                ->formatStateUsing(fn (?string $state): string => filled($state)
                                    ? Str::headline($state)
                                    : 'Not specified'),
                        ]),
                    ]),
                Section::make('Request summary')
                    ->description('Service and pet details are listed in the tabs below.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                        ])->schema([
                            TextEntry::make('status')
                                ->badge()
                                ->formatStateUsing(fn (BookingRequestStatus|string|null $state): string => $state instanceof BookingRequestStatus
                                    ? BookingRequestStatus::options()[$state->value]
                                    : (BookingRequestStatus::options()[$state] ?? 'Unknown')),
                            TextEntry::make('source')
                                ->label('Submitted from')
                                ->formatStateUsing(fn (?string $state): string => filled($state)
                                    ? Str::headline($state)
                                    : 'Website booking form'),
                            TextEntry::make('created_at')
                                ->label('Received')
                                ->dateTime('M j, Y g:i A'),
                            TextEntry::make('status_changed_at')
                                ->label('Status updated')
                                ->dateTime('M j, Y g:i A'),
                        ]),
                    ]),
                Section::make('Customer message')
                    ->visible(fn (BookingRequest $record): bool => filled($record->message))
                    ->schema([
                        TextEntry::make('message')
                            ->label('Message')
                            ->prose()
                            ->columnSpanFull(),
                    ]),
                Section::make('Quote summary')
                    ->visible(fn (BookingRequest $record): bool => filled($record->quote_amount)
                        || filled($record->quote_currency)
                        || filled($record->quote_notes))
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                        ])->schema([
                            TextEntry::make('quote_amount')
                                ->label('Total quote')
                                ->money('NGN'),
                            TextEntry::make('quote_currency')
                                ->label('Currency'),
                            TextEntry::make('quote_notes')
                                ->label('Quote notes')
                                ->prose()
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Staff notes')
                    ->visible(fn (BookingRequest $record): bool => filled($record->internal_notes))
                    ->schema([
                        TextEntry::make('internal_notes')
                            ->label('Internal notes')
                            ->prose()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
