<?php

namespace App\Filament\Resources\BookingRequests\Schemas;

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BookingRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer')
                    ->description('Correct contact details here. Pet and service information is managed in the sections below; saved corrections are recorded in the activity log.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Name')
                                ->required()
                                ->maxLength(120),
                            TextInput::make('phone')
                                ->label('Phone')
                                ->tel()
                                ->required()
                                ->maxLength(40),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Select::make('preferred_contact_method')
                                ->label('Preferred contact')
                                ->options([
                                    'phone' => 'Phone',
                                    'email' => 'Email',
                                    'whatsapp' => 'WhatsApp',
                                ])
                                ->placeholder('No preference')
                                ->native(false),
                        ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Request')
                    ->description('Request status is changed with the guarded progress actions above. The service and pet records below contain the full submitted request.')
                    ->schema([
                        Grid::make(2)->schema([
                            Placeholder::make('request_contents')
                                ->label('Request includes')
                                ->content(fn (BookingRequest $record): string => sprintf(
                                    '%d %s · %d %s',
                                    $record->pets()->count(),
                                    $record->pets()->count() === 1 ? 'pet' : 'pets',
                                    $record->services()->count(),
                                    $record->services()->count() === 1 ? 'service' : 'services',
                                )),
                            TextInput::make('status')
                                ->label('Request status')
                                ->formatStateUsing(fn (BookingRequestStatus|string|null $state): string => $state instanceof BookingRequestStatus
                                    ? BookingRequestStatus::options()[$state->value]
                                    : (BookingRequestStatus::options()[$state] ?? (string) $state))
                                ->disabled()
                                ->dehydrated(false),
                            TextInput::make('source')
                                ->label('Submission source')
                                ->disabled()
                                ->dehydrated(false),
                            TextInput::make('id')
                                ->label('Request reference')
                                ->disabled()
                                ->dehydrated(false),
                            Textarea::make('context')
                                ->label('Submission context')
                                ->formatStateUsing(fn (?array $state): string => self::formatContext($state))
                                ->disabled()
                                ->dehydrated(false)
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Customer message and staff notes')
                    ->schema([
                        Textarea::make('message')
                            ->label('Customer message')
                            ->rows(4)
                            ->maxLength(5000)
                            ->helperText('This is customer-submitted information. Corrections are recorded in the activity log.')
                            ->columnSpanFull(),
                        Textarea::make('internal_notes')
                            ->label('Internal notes')
                            ->rows(5)
                            ->helperText('Never shown on public pages or in customer messages.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Quote summary')
                    ->description('The service quotes below are authoritative. These parent values are calculated summaries.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('quote_amount')
                                ->label('Total')
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
                                ->label('Quote notes summary')
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

    /**
     * @param  array<string, mixed>|null  $context
     */
    private static function formatContext(?array $context): string
    {
        if ($context === [] || $context === null) {
            return 'Not provided';
        }

        return collect($context)
            ->map(fn (mixed $value, string|int $key): string => Str::headline((string) $key).': '.self::formatContextValue($value))
            ->implode(PHP_EOL);
    }

    private static function formatContextValue(mixed $value): string
    {
        if (is_array($value)) {
            return collect($value)
                ->map(fn (mixed $nestedValue, string|int $key): string => Str::headline((string) $key).': '.self::formatContextValue($nestedValue))
                ->implode('; ');
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return 'Not provided';
    }
}
