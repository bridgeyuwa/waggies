<?php

namespace App\Filament\Resources\BookingRequests\RelationManagers;

use App\Filament\Resources\BookingRequests\Schemas\BookingRequestDetailFields;
use App\Models\BookingRequestPet;
use App\Support\BookingRequestCorrectionLogger;
use Filament\Actions\Action;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class PetsRelationManager extends RelationManager
{
    protected static string $relationship = 'pets';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Pet')
                    ->searchable(),
                TextColumn::make('species')
                    ->label('Species')
                    ->placeholder('Not specified'),
                TextColumn::make('details.size')
                    ->label('Size')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'small' => 'Small',
                        'medium' => 'Medium',
                        'large' => 'Large',
                        'manual-review' => 'Manual review',
                        default => filled($state) ? $state : 'Not specified',
                    }),
                TextColumn::make('breed')
                    ->label('Breed')
                    ->placeholder('Not specified'),
                TextColumn::make('age')
                    ->label('Age')
                    ->placeholder('Not specified'),
                TextColumn::make('sex')
                    ->label('Sex')
                    ->placeholder('Not specified'),
                TextColumn::make('notes')
                    ->label('Submitted care notes')
                    ->placeholder('Not specified')
                    ->limit(120)
                    ->wrap(),
            ])
            ->recordActions([
                Action::make('editPetDetails')
                    ->label('Edit pet details')
                    ->modalHeading('Correct submitted pet details')
                    ->modalDescription('Corrections are recorded with the staff member and previous values.')
                    ->modalWidth('4xl')
                    ->schema([
                        Section::make('Pet information')
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(80),
                                TextInput::make('species')
                                    ->label('Species')
                                    ->required()
                                    ->maxLength(40),
                                TextInput::make('breed')
                                    ->label('Breed')
                                    ->maxLength(120),
                                TextInput::make('age')
                                    ->label('Age or age range')
                                    ->maxLength(80),
                                TextInput::make('sex')
                                    ->label('Sex')
                                    ->maxLength(40),
                                TextInput::make('size')
                                    ->label('Size')
                                    ->helperText('For example: small, medium, large, or manual review.')
                                    ->maxLength(80),
                                Textarea::make('notes')
                                    ->label('Submitted care notes')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                        Section::make('Other stored details')
                            ->schema([
                                KeyValue::make('additional_details')
                                    ->label('Additional pet details')
                                    ->helperText('Leave a value blank to clear it. Enter an object or list as valid JSON to keep it structured.')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->fillForm(function (BookingRequestPet $record): array {
                        $details = $record->details ?? [];

                        return [
                            'name' => $record->name,
                            'species' => $record->species,
                            'breed' => $record->breed,
                            'age' => $record->age,
                            'sex' => $record->sex,
                            'size' => $details['size'] ?? null,
                            'notes' => $record->notes,
                            'additional_details' => BookingRequestDetailFields::additionalDetails($details, ['size']),
                        ];
                    })
                    ->action(function (array $data, BookingRequestPet $record): void {
                        $bookingRequest = $record->bookingRequest()->firstOrFail();
                        $isPrimaryPet = $bookingRequest->pet_name === $record->name
                            && $bookingRequest->pet_type === $record->species;
                        $originalDetails = $record->details ?? [];
                        $originalAdditionalDetails = BookingRequestDetailFields::additionalDetails($originalDetails, ['size']);
                        $details = $originalDetails;
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

                        if (filled($data['size'] ?? null)) {
                            $details['size'] = trim((string) $data['size']);
                        } else {
                            unset($details['size']);
                        }

                        $oldCorrectionValues = [
                            'name' => $record->name,
                            'species' => $record->species,
                            'breed' => $record->breed,
                            'age' => $record->age,
                            'sex' => $record->sex,
                            'notes' => $record->notes,
                            'details' => $originalDetails,
                        ];
                        $newCorrectionValues = [
                            'name' => trim((string) $data['name']),
                            'species' => trim((string) $data['species']),
                            'breed' => filled($data['breed'] ?? null) ? trim((string) $data['breed']) : null,
                            'age' => filled($data['age'] ?? null) ? trim((string) $data['age']) : null,
                            'sex' => filled($data['sex'] ?? null) ? trim((string) $data['sex']) : null,
                            'notes' => filled($data['notes'] ?? null) ? trim((string) $data['notes']) : null,
                            'details' => $details,
                        ];

                        DB::transaction(function () use (
                            $bookingRequest,
                            $data,
                            $details,
                            $isPrimaryPet,
                            $newCorrectionValues,
                            $oldCorrectionValues,
                            $record,
                        ): void {
                            $record->update([
                                'name' => trim((string) $data['name']),
                                'species' => trim((string) $data['species']),
                                'breed' => filled($data['breed'] ?? null) ? trim((string) $data['breed']) : null,
                                'age' => filled($data['age'] ?? null) ? trim((string) $data['age']) : null,
                                'sex' => filled($data['sex'] ?? null) ? trim((string) $data['sex']) : null,
                                'notes' => filled($data['notes'] ?? null) ? trim((string) $data['notes']) : null,
                                'details' => $details,
                            ]);

                            app(BookingRequestCorrectionLogger::class)->record(
                                $record,
                                'Pet details corrected',
                                $oldCorrectionValues,
                                $newCorrectionValues,
                            );

                            if ($isPrimaryPet) {
                                $bookingRequest->forceFill([
                                    'pet_name' => $record->name,
                                    'pet_type' => $record->species,
                                ])->saveQuietly();
                            }
                        });

                        Notification::make()
                            ->title('Pet details updated')
                            ->body('Review the service quote if this correction affects pricing or care requirements.')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
