<?php

namespace App\Filament\Resources\BookingRequests\RelationManagers;

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use App\Models\BookingRequestService;
use App\Support\BookingPricingCatalog;
use DomainException;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'services';

    public function form(Schema $schema): Schema
    {
        return $schema->components($this->quoteForm());
    }

    /**
     * @return array<int, TextInput|Textarea>
     */
    private function quoteForm(): array
    {
        return [
            TextInput::make('quote_amount')
                ->label('Quote amount')
                ->numeric()
                ->integer()
                ->minValue(0)
                ->helperText('Enter the amount offered for this service, or leave blank when the quote is explained in notes.'),
            TextInput::make('quote_currency')
                ->label('Currency')
                ->default(app(BookingPricingCatalog::class)->currency())
                ->maxLength(3),
            Textarea::make('quote_notes')
                ->label('Quote notes')
                ->rows(4)
                ->helperText('Use notes when the service needs a manual quote explanation.'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('service_key')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('pets'))
            ->columns([
                TextColumn::make('service_key')
                    ->label('Service')
                    ->formatStateUsing(fn (?string $state): string => BookingRequest::serviceOptions()[$state] ?? (string) $state),
                TextColumn::make('service_variant')
                    ->label('Options / care needs')
                    ->formatStateUsing(fn (?string $state, BookingRequestService $record): ?string => app(BookingPricingCatalog::class)->serviceSelectionSummary($record->toArray(), $record->pets->toArray()))
                    ->placeholder('Not specified'),
                TextColumn::make('assigned_pets')
                    ->label('Assigned pets')
                    ->state(fn (BookingRequestService $record): ?string => $record->pets->pluck('name')->filter()->join(', ') ?: null)
                    ->placeholder('Not assigned'),
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
                TextColumn::make('details')
                    ->label('Care details')
                    ->formatStateUsing(fn (mixed $state): string => $this->payloadSummary($state))
                    ->placeholder('Not specified')
                    ->wrap(),
                TextColumn::make('price_snapshot')
                    ->label('Pricing snapshot')
                    ->formatStateUsing(fn (mixed $state): string => $this->payloadSummary($state))
                    ->placeholder('Not captured')
                    ->wrap(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (BookingRequestStatus|string|null $state): string => $state instanceof BookingRequestStatus
                        ? BookingRequestStatus::options()[$state->value]
                        : (BookingRequestStatus::options()[$state] ?? (string) $state)),
                TextColumn::make('quote_amount')
                    ->label('Quote')
                    ->numeric()
                    ->placeholder('Not set'),
                TextColumn::make('quote_currency')
                    ->label('Currency')
                    ->placeholder('—'),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit quote')
                    ->modalHeading('Edit service quote')
                    ->fillForm(fn (BookingRequestService $record): array => [
                        'quote_amount' => $record->quote_amount,
                        'quote_currency' => $record->quote_currency,
                        'quote_notes' => $record->quote_notes,
                    ])
                    ->schema($this->quoteForm())
                    ->action(function (array $data, BookingRequestService $record): void {
                        try {
                            $record->updateOperationalQuote(
                                amount: filled($data['quote_amount'] ?? null) ? (int) $data['quote_amount'] : null,
                                currency: $data['quote_currency'] ?? null,
                                notes: $data['quote_notes'] ?? null,
                            );
                        } catch (DomainException $exception) {
                            Notification::make()
                                ->title($exception->getMessage())
                                ->danger()
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->title('Service quote updated')
                            ->success()
                            ->send();
                    }),
                ActionGroup::make([
                    $this->statusAction('startServiceReview', 'Start review', BookingRequestStatus::Reviewing),
                    $this->statusAction('markServiceQuoted', 'Mark quoted', BookingRequestStatus::Quoted, requiresQuote: true),
                    $this->statusAction('confirmService', 'Confirm service', BookingRequestStatus::Confirmed, requiresQuote: true),
                    $this->statusAction('declineService', 'Decline service', BookingRequestStatus::Declined),
                    $this->statusAction('cancelService', 'Cancel service', BookingRequestStatus::Cancelled),
                    $this->statusAction('completeService', 'Mark completed', BookingRequestStatus::Completed),
                ]),
            ]);
    }

    private function statusAction(
        string $name,
        string $label,
        BookingRequestStatus $status,
        bool $requiresQuote = false,
    ): Action {
        return Action::make($name)
            ->label($label)
            ->action(function (BookingRequestService $record) use ($status, $label): void {
                $record->transitionTo($status);

                Notification::make()
                    ->title("Service {$label}")
                    ->success()
                    ->send();
            })
            ->disabled(fn (BookingRequestService $record): bool => ! $record->canTransitionTo($status)
                || ($requiresQuote && ! $record->hasQuoteDecision()));
    }

    private function payloadSummary(mixed $payload): string
    {
        if ($payload === null || $payload === '' || $payload === []) {
            return 'Not specified';
        }

        $summary = is_string($payload)
            ? $payload
            : json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return Str::limit($summary ?: 'Not specified', 180);
    }
}
