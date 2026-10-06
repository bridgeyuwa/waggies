<?php

namespace App\Filament\Resources\BookingRequests\Tables;

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use App\Support\BookingPricingCatalog;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookingRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['services.pets']))
            ->columns([
                TextColumn::make('name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('requested_services')
                    ->label('Requested services')
                    ->state(fn (BookingRequest $record): string => $record->services
                        ->map(fn ($service): string => app(BookingPricingCatalog::class)->serviceSummary(
                            $service->toArray(),
                            $service->pets->toArray(),
                        ))
                        ->filter()
                        ->join(', ') ?: 'No services'),
                TextColumn::make('services_count')
                    ->label('Services')
                    ->counts('services')
                    ->sortable(),
                TextColumn::make('pets_count')
                    ->label('Pets')
                    ->counts('pets')
                    ->sortable(),
                TextColumn::make('requested_date')
                    ->label('Requested date')
                    ->date()
                    ->placeholder('Flexible')
                    ->sortable(),
                TextColumn::make('requested_time')
                    ->label('Time')
                    ->placeholder('Any time'),
                TextColumn::make('status')
                    ->label('Request status')
                    ->badge()
                    ->formatStateUsing(fn (BookingRequestStatus|string|null $state): string => $state instanceof BookingRequestStatus
                        ? BookingRequestStatus::options()[$state->value]
                        : (BookingRequestStatus::options()[$state] ?? (string) $state))
                    ->sortable(),
                TextColumn::make('next_step')
                    ->label('Staff next step')
                    ->state(fn (BookingRequest $record): string => self::nextStep($record))
                    ->wrap(),
                TextColumn::make('quote_amount')
                    ->label('Quote total')
                    ->numeric()
                    ->placeholder('Not quoted'),
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
                ViewAction::make()
                    ->label('View booking'),
                EditAction::make()
                    ->label('Review booking')
                    ->color('primary'),
                DeleteAction::make()
                    ->label('Delete booking'),
            ]);
    }

    private static function nextStep(BookingRequest $record): string
    {
        $status = $record->status instanceof BookingRequestStatus
            ? $record->status
            : BookingRequestStatus::from((string) $record->status);

        return match ($status) {
            BookingRequestStatus::New => 'Start review',
            BookingRequestStatus::Reviewing => $record->services->isNotEmpty()
                && $record->services->every(fn ($service): bool => $service->hasQuoteDecision())
                    ? 'Mark request quoted'
                    : 'Prepare service quotes',
            BookingRequestStatus::Quoted => 'Review and progress each service',
            BookingRequestStatus::AwaitingCustomer => 'Waiting for customer',
            BookingRequestStatus::Confirmed => 'Complete confirmed services',
            BookingRequestStatus::Declined,
            BookingRequestStatus::Cancelled,
            BookingRequestStatus::Completed => 'No further action',
        };
    }
}
