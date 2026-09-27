<?php

namespace App\Filament\Resources\BookingRequests\RelationManagers;

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'services';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('service_key')
            ->columns([
                TextColumn::make('service_key')
                    ->label('Service')
                    ->formatStateUsing(fn (?string $state): string => BookingRequest::serviceOptions()[$state] ?? (string) $state),
                TextColumn::make('service_variant')
                    ->label('Variant')
                    ->placeholder('Not specified'),
                TextColumn::make('pricing_tier')
                    ->label('Tier')
                    ->placeholder('Not specified'),
                TextColumn::make('requested_date')
                    ->label('Requested date')
                    ->date()
                    ->placeholder('Flexible'),
                TextColumn::make('requested_time')
                    ->label('Time')
                    ->placeholder('Any time'),
                TextColumn::make('location')
                    ->placeholder('Not specified')
                    ->wrap(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (BookingRequestStatus|string|null $state): string => $state instanceof BookingRequestStatus
                        ? BookingRequestStatus::options()[$state->value]
                        : (BookingRequestStatus::options()[$state] ?? (string) $state)),
            ]);
    }
}
