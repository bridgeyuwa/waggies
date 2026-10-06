<?php

namespace App\Filament\Resources\BookingRequests\RelationManagers;

use App\Enums\BookingRequestStatus;
use App\Filament\Resources\BookingRequests\Schemas\BookingRequestDetailFields;
use App\Models\BookingRequest;
use App\Models\BookingRequestPet;
use App\Models\BookingRequestService;
use App\Support\BookingPricingCatalog;
use App\Support\BookingRequestCorrectionLogger;
use App\Support\BookingRequestSchema;
use DomainException;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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
                    ->label('Selected options')
                    ->state(fn (BookingRequestService $record): ?string => app(BookingPricingCatalog::class)->serviceSelectionSummary($record->toArray(), $record->pets->toArray()))
                    ->placeholder('Not specified')
                    ->wrap(),
                TextColumn::make('assigned_pets')
                    ->label('Assigned pets')
                    ->state(fn (BookingRequestService $record): ?string => $record->pets->pluck('name')->filter()->join(', ') ?: null)
                    ->placeholder('Not assigned')
                    ->wrap(),
                TextColumn::make('requested_date')
                    ->label('Requested date')
                    ->date()
                    ->placeholder('Flexible'),
                TextColumn::make('requested_time')
                    ->label('Time')
                    ->placeholder('Any time'),
                TextColumn::make('location')
                    ->label('Location')
                    ->placeholder('Not specified')
                    ->wrap(),
                TextColumn::make('details')
                    ->label('Submitted care details')
                    ->state(fn (BookingRequestService $record): string => BookingRequestDetailFields::serviceSummary(
                        $record->details ?? [],
                        $record->service_key,
                        $record->service_variant,
                    ))
                    ->placeholder('Not specified')
                    ->limit(180)
                    ->tooltip(fn (BookingRequestService $record): string => BookingRequestDetailFields::serviceSummary(
                        $record->details ?? [],
                        $record->service_key,
                        $record->service_variant,
                    ))
                    ->wrap(),
                TextColumn::make('price_snapshot')
                    ->label('Intake pricing context')
                    ->state(fn (BookingRequestService $record): string => $this->priceSnapshotSummary($record->price_snapshot))
                    ->placeholder('Not captured at intake')
                    ->tooltip(fn (BookingRequestService $record): string => $this->priceSnapshotSummary($record->price_snapshot))
                    ->wrap(),
                TextColumn::make('status')
                    ->label('Service status')
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
                $this->editRequestDetailsAction(),
                Action::make('editQuote')
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
                ])
                    ->label('Progress service')
                    ->button(),
            ]);
    }

    private function editRequestDetailsAction(): Action
    {
        return Action::make('editRequestDetails')
            ->label('Edit request details')
            ->modalHeading('Correct submitted service details')
            ->modalDescription('Corrections are recorded with the staff member and previous values. The original intake pricing context is preserved.')
            ->modalWidth('5xl')
            ->schema([
                Section::make('Service and schedule')
                    ->description('Correct the requested service, timing, and location.')
                    ->columns(2)
                    ->schema([
                        Select::make('service_key')
                            ->label('Service')
                            ->options(BookingRequestSchema::allServiceOptions())
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Select $component) => $this->refreshServiceFields($component)),
                        Select::make('service_variant')
                            ->label('Service option')
                            ->options(fn (Get $get): array => BookingRequestSchema::allVariantOptions($get('service_key')))
                            ->searchable()
                            ->placeholder('No single option')
                            ->visible(fn (Get $get): bool => BookingRequestSchema::serviceSelectionMode($get('service_key')) === 'single'
                                && BookingRequestSchema::allVariantOptions($get('service_key')) !== [])
                            ->required(fn (Get $get): bool => app(BookingPricingCatalog::class)->serviceOptionRequired((string) $get('service_key')))
                            ->live()
                            ->afterStateUpdated(fn (Select $component) => $this->refreshServiceFields($component)),
                        TextInput::make('pricing_tier')
                            ->label('Pricing tier')
                            ->maxLength(80),
                        TimePicker::make('requested_time')
                            ->label('Requested time'),
                        TextInput::make('location')
                            ->label('Location')
                            ->maxLength(255),
                        Section::make('Requested timing')
                            ->key('serviceScheduleFields')
                            ->columns(2)
                            ->schema(fn (Get $get): array => BookingRequestDetailFields::serviceScheduleFields(
                                filled($get('service_key')) ? (string) $get('service_key') : null,
                                filled($get('service_variant')) ? (string) $get('service_variant') : null,
                            ))
                            ->visible(fn (Get $get): bool => BookingRequestDetailFields::serviceScheduleFields(
                                filled($get('service_key')) ? (string) $get('service_key') : null,
                                filled($get('service_variant')) ? (string) $get('service_variant') : null,
                            ) !== [])
                            ->columnSpanFull(),
                        Section::make('Submitted options and care details')
                            ->key('serviceDetailFields')
                            ->columns(2)
                            ->schema(fn (Get $get, BookingRequestService $record): array => BookingRequestDetailFields::serviceFields(
                                filled($get('service_key')) ? (string) $get('service_key') : null,
                                filled($get('service_variant')) ? (string) $get('service_variant') : null,
                                array_key_exists('message', $record->details ?? []),
                            ))
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Pets receiving this service')
                    ->description('Choose every pet this service applies to.')
                    ->schema([
                        Select::make('pet_ids')
                            ->label('Assigned pets')
                            ->options(fn (): array => $this->assignedPetOptions())
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ])
            ->fillForm(function (BookingRequestService $record): array {
                $details = $record->details ?? [];

                if ($record->service_key === 'vet-care'
                    && empty($details['care_needs'])
                    && filled($record->service_variant)) {
                    $details['care_needs'] = [$record->service_variant];
                }

                $hasMessage = array_key_exists('message', $details);

                return [
                    'service_key' => $record->service_key,
                    'service_variant' => $record->service_variant,
                    'pricing_tier' => $record->pricing_tier,
                    'requested_date' => $record->requested_date?->format('Y-m-d'),
                    'requested_end_date' => $record->requested_end_date?->format('Y-m-d'),
                    'requested_time' => $record->requested_time,
                    'location' => $record->location,
                    'details' => $details,
                    'additional_details' => BookingRequestDetailFields::additionalDetails(
                        $details,
                        BookingRequestDetailFields::serviceDetailKeys($record->service_key, $record->service_variant, $hasMessage),
                    ),
                    'pet_ids' => $record->pets->modelKeys(),
                ];
            })
            ->action(function (array $data, BookingRequestService $record): void {
                $bookingRequest = $record->bookingRequest()->firstOrFail();
                $isPrimaryService = $this->matchesParentSummary($bookingRequest, $record);
                $originalDetails = $record->details ?? [];
                $originalAdditionalDetails = BookingRequestDetailFields::additionalDetails(
                    $originalDetails,
                    BookingRequestDetailFields::serviceDetailKeys(
                        $record->service_key,
                        $record->service_variant,
                        array_key_exists('message', $originalDetails),
                    ),
                );
                $originalPetIds = $record->pets()
                    ->pluck('booking_request_pets.id')
                    ->map(fn (mixed $id): string => (string) $id)
                    ->sort()
                    ->values()
                    ->all();
                $petOptions = $this->assignedPetOptions();
                $originalAssignedPets = array_map(fn (string $id): string => $petOptions[$id] ?? $id, $originalPetIds);
                $petIds = array_values(array_unique(array_map('strval', $data['pet_ids'] ?? [])));
                $sortedPetIds = $petIds;
                sort($sortedPetIds, SORT_STRING);
                $assignedPets = array_map(fn (string $id): string => $petOptions[$id] ?? $id, $sortedPetIds);
                $serviceKey = (string) ($data['service_key'] ?? $record->service_key);
                $serviceVariant = array_key_exists('service_variant', $data)
                    ? ($data['service_variant'] ?: null)
                    : ($serviceKey === $record->service_key ? $record->service_variant : null);

                if (BookingRequestSchema::serviceSelectionMode($serviceKey) === 'multiple') {
                    $serviceVariant = null;
                }
                $details = array_replace($originalDetails, is_array($data['details'] ?? null) ? $data['details'] : []);
                $additionalDetails = BookingRequestDetailFields::normalizeAdditionalDetails(is_array($data['additional_details'] ?? null)
                    ? $data['additional_details']
                    : []);

                foreach ($originalAdditionalDetails as $key => $value) {
                    if (array_key_exists($key, $additionalDetails) && $additionalDetails[$key] === '') {
                        unset($details[$key]);
                    }
                }

                $additionalDetails = array_filter($additionalDetails, static fn (mixed $value): bool => $value !== '');
                $details = array_replace($details, $additionalDetails);
                $requestedDate = array_key_exists('requested_date', $data)
                    ? ($data['requested_date'] ?: null)
                    : ($serviceKey === $record->service_key ? $record->requested_date?->format('Y-m-d') : null);
                $requestedEndDate = array_key_exists('requested_end_date', $data)
                    ? ($data['requested_end_date'] ?: null)
                    : ($serviceKey === $record->service_key ? $record->requested_end_date?->format('Y-m-d') : null);

                if ($serviceKey === 'boarding') {
                    $requestedDate = $details['check_in'] ?? null;
                    $requestedEndDate = $details['check_out'] ?? null;
                } elseif ($serviceKey === 'relocation') {
                    if (($details['travel_timing'] ?? null) === 'not_decided') {
                        $requestedDate = null;
                        $requestedEndDate = null;
                    } elseif (($details['travel_timing'] ?? null) === 'exact') {
                        $requestedEndDate = null;
                    }
                }
                $oldCorrectionValues = [
                    'service_key' => $record->service_key,
                    'service_variant' => $record->service_variant,
                    'pricing_tier' => $record->pricing_tier,
                    'requested_date' => $record->requested_date?->format('Y-m-d'),
                    'requested_end_date' => $record->requested_end_date?->format('Y-m-d'),
                    'requested_time' => $record->requested_time,
                    'location' => $record->location,
                    'details' => $originalDetails,
                ];
                $newCorrectionValues = [
                    'service_key' => $serviceKey,
                    'service_variant' => $serviceVariant,
                    'pricing_tier' => filled($data['pricing_tier'] ?? null) ? trim((string) $data['pricing_tier']) : null,
                    'requested_date' => $requestedDate,
                    'requested_end_date' => $requestedEndDate,
                    'requested_time' => filled($data['requested_time'] ?? null) ? $data['requested_time'] : null,
                    'location' => filled($data['location'] ?? null) ? trim((string) $data['location']) : null,
                    'details' => $details,
                ];

                DB::transaction(function () use (
                    $bookingRequest,
                    $data,
                    $details,
                    $isPrimaryService,
                    $newCorrectionValues,
                    $oldCorrectionValues,
                    $originalPetIds,
                    $originalAssignedPets,
                    $petIds,
                    $record,
                    $requestedDate,
                    $requestedEndDate,
                    $serviceKey,
                    $serviceVariant,
                    $sortedPetIds,
                    $assignedPets,
                ): void {
                    $record->update([
                        'service_key' => $serviceKey,
                        'service_variant' => $serviceVariant,
                        'pricing_tier' => filled($data['pricing_tier'] ?? null) ? trim((string) $data['pricing_tier']) : null,
                        'requested_date' => $requestedDate,
                        'requested_end_date' => $requestedEndDate,
                        'requested_time' => filled($data['requested_time'] ?? null) ? $data['requested_time'] : null,
                        'location' => filled($data['location'] ?? null) ? trim((string) $data['location']) : null,
                        'details' => $details,
                    ]);

                    $record->pets()->sync($petIds);

                    app(BookingRequestCorrectionLogger::class)->record(
                        $record,
                        'Service request details corrected',
                        $oldCorrectionValues,
                        $newCorrectionValues,
                    );

                    if ($isPrimaryService) {
                        $bookingRequest->forceFill([
                            'service_key' => $record->service_key,
                            'service_variant' => $record->service_variant,
                            'pricing_tier' => $record->pricing_tier,
                            'requested_date' => $record->requested_date?->format('Y-m-d') ?? data_get($record->details, 'check_in'),
                            'requested_time' => $record->requested_time,
                            'location' => $record->location ?? data_get($record->details, 'pickup'),
                        ])->saveQuietly();
                    }

                    if ($isPrimaryService && array_key_exists('message', $details)) {
                        $bookingRequest->forceFill(['message' => $details['message']])->save();
                    }

                    if ($originalPetIds !== $sortedPetIds) {
                        app(BookingRequestCorrectionLogger::class)->record(
                            $record,
                            'Service pet assignments corrected',
                            ['assigned_pets' => $originalAssignedPets],
                            ['assigned_pets' => $assignedPets],
                        );
                    }
                });

                Notification::make()
                    ->title('Service request details updated')
                    ->body('The original intake pricing context is preserved. Review the service quote if these corrections affect pricing.')
                    ->success()
                    ->send();
            });
    }

    /**
     * @return array<string, string>
     */
    private function assignedPetOptions(): array
    {
        return $this->getOwnerRecord()
            ->pets()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (BookingRequestPet $pet): array => [
                (string) $pet->getKey() => implode(' · ', array_filter([
                    $pet->name,
                    Str::headline((string) $pet->species),
                    $pet->breed,
                ])),
            ])
            ->all();
    }

    private function matchesParentSummary(BookingRequest $bookingRequest, BookingRequestService $service): bool
    {
        $serviceDate = $service->requested_date?->format('Y-m-d') ?? data_get($service->details, 'check_in');
        $requestDate = $bookingRequest->requested_date?->format('Y-m-d');

        return $bookingRequest->service_key === $service->service_key
            && $bookingRequest->service_variant === $service->service_variant
            && $bookingRequest->pricing_tier === $service->pricing_tier
            && $requestDate === $serviceDate
            && $bookingRequest->requested_time === $service->requested_time
            && $bookingRequest->location === ($service->location ?? data_get($service->details, 'pickup'));
    }

    private function refreshServiceFields(Select $component): void
    {
        $container = $component->getContainer();
        $container->getComponent('serviceScheduleFields')->getChildSchema()->fill();
        $container->getComponent('serviceDetailFields')->getChildSchema()->fill();
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

    private function priceSnapshotSummary(mixed $snapshot): string
    {
        if (! is_array($snapshot) || $snapshot === []) {
            return 'Not captured at intake';
        }

        $status = Str::headline((string) ($snapshot['status'] ?? 'Pricing context'));
        $currency = (string) ($snapshot['currency'] ?? app(BookingPricingCatalog::class)->currency());
        $petLines = collect(is_array($snapshot['lines'] ?? null) ? $snapshot['lines'] : [])
            ->filter(fn (mixed $line): bool => is_array($line))
            ->map(function (array $line) use ($currency): string {
                $lineAmount = is_numeric($line['amount'] ?? null) ? ' · '.$currency.' '.number_format((int) $line['amount']) : '';
                $lineStatus = filled($line['status'] ?? null) ? Str::headline((string) $line['status']) : null;
                $size = filled($line['size'] ?? null) ? Str::headline((string) $line['size']) : null;

                return implode(' ', array_filter([
                    (string) ($line['pet_name'] ?? 'Pet'),
                    $lineStatus ? "({$lineStatus}{$lineAmount})" : trim($lineAmount),
                    $size ? "— {$size}" : null,
                ]));
            })
            ->all();

        if (! is_numeric($snapshot['amount'] ?? null)) {
            return implode(' · ', array_filter([
                $status,
                filled($snapshot['reason'] ?? null) ? (string) $snapshot['reason'] : null,
                ...$petLines,
            ]));
        }

        $amount = number_format((int) $snapshot['amount']);
        $maximum = is_numeric($snapshot['max_amount'] ?? null) ? (int) $snapshot['max_amount'] : null;
        $amountLabel = $maximum !== null && $maximum !== (int) $snapshot['amount']
            ? "{$amount}–".number_format($maximum)
            : $amount;

        return implode(' · ', array_filter([
            $status,
            "{$currency} {$amountLabel}",
            ...$petLines,
        ]));
    }
}
