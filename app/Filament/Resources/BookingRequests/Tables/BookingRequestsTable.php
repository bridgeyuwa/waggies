<?php

namespace App\Filament\Resources\BookingRequests\Tables;

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service_key')
                    ->label('Service')
                    ->formatStateUsing(fn (?string $state): string => BookingRequest::serviceOptions()[$state] ?? (string) $state)
                    ->sortable(),
                TextColumn::make('requested_date')
                    ->label('Requested date')
                    ->date()
                    ->sortable(),
                TextColumn::make('requested_time')
                    ->label('Time')
                    ->placeholder('Any time'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (BookingRequestStatus|string|null $state): string => $state instanceof BookingRequestStatus
                        ? BookingRequestStatus::options()[$state->value]
                        : (BookingRequestStatus::options()[$state] ?? (string) $state))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(BookingRequestStatus::options()),
                SelectFilter::make('service_key')
                    ->label('Service')
                    ->options(BookingRequest::serviceOptions()),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchable()
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
