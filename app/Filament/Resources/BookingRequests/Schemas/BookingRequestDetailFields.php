<?php

namespace App\Filament\Resources\BookingRequests\Schemas;

use App\Support\BookingRequestSchema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Str;

class BookingRequestDetailFields
{
    /**
     * @return array<int, Field>
     */
    public static function serviceFields(?string $service, ?string $variant, bool $hasMessage): array
    {
        $fields = [];

        foreach (BookingRequestSchema::serviceFields($service ?? '', $variant) as $definition) {
            if (($definition['scope'] ?? 'details') !== 'details') {
                continue;
            }

            $fields[] = self::makeField($definition);
        }

        if ($service === 'vet-care') {
            $fields[] = Select::make('details.care_needs')
                ->label('Veterinary care needs')
                ->options(BookingRequestSchema::allVariantOptions('vet-care'))
                ->multiple()
                ->searchable()
                ->required()
                ->columnSpanFull();
        }

        if ($hasMessage) {
            $fields[] = Textarea::make('details.message')
                ->label('Customer message for this service')
                ->rows(3)
                ->columnSpanFull();
        }

        $fields[] = KeyValue::make('additional_details')
            ->label('Other stored details')
            ->helperText('Existing extra values stay attached to this service. Leave a value blank to clear it, or enter an object or list as valid JSON to keep it structured.')
            ->columnSpanFull();

        return $fields;
    }

    /**
     * @return array<int, Field>
     */
    public static function serviceScheduleFields(?string $service, ?string $variant): array
    {
        $fields = [];

        foreach (BookingRequestSchema::serviceFields($service ?? '', $variant) as $definition) {
            if (($definition['scope'] ?? 'details') !== 'service') {
                continue;
            }

            $fields[] = self::makeField($definition, '');
        }

        return $fields;
    }

    /**
     * @param  array<string, mixed>  $details
     * @return array<int, string>
     */
    public static function serviceDetailKeys(?string $service, ?string $variant, bool $hasMessage): array
    {
        $keys = collect(BookingRequestSchema::serviceFields($service ?? '', $variant))
            ->filter(fn (array $field): bool => ($field['scope'] ?? 'details') === 'details')
            ->pluck('key')
            ->all();

        if ($service === 'vet-care') {
            $keys[] = 'care_needs';
        }

        if ($hasMessage) {
            $keys[] = 'message';
        }

        return array_values(array_unique($keys));
    }

    /**
     * @param  array<string, mixed>  $details
     * @param  array<int, string>  $knownKeys
     * @return array<string, string>
     */
    public static function additionalDetails(array $details, array $knownKeys): array
    {
        return collect($details)
            ->except($knownKeys)
            ->map(fn (mixed $value): string => self::formatAdditionalValue($value))
            ->all();
    }

    /**
     * @param  array<string, mixed>  $details
     * @return array<string, mixed>
     */
    public static function normalizeAdditionalDetails(array $details): array
    {
        return collect($details)
            ->map(fn (mixed $value): mixed => self::decodeStructuredValue($value))
            ->all();
    }

    /**
     * @param  array<string, mixed>  $details
     */
    public static function serviceSummary(array $details, ?string $service, ?string $variant): string
    {
        $summary = [];
        $handledKeys = [];

        foreach (BookingRequestSchema::serviceFields($service ?? '', $variant) as $definition) {
            if (($definition['scope'] ?? 'details') !== 'details') {
                continue;
            }

            $key = (string) ($definition['key'] ?? '');
            $value = $details[$key] ?? null;
            $handledKeys[] = $key;

            if (! self::hasValue($value)) {
                continue;
            }

            $summary[] = sprintf(
                '%s: %s',
                (string) ($definition['label'] ?? Str::headline($key)),
                self::formatValue($value, is_array($definition['options'] ?? null) ? $definition['options'] : null),
            );
        }

        if ($service === 'vet-care' && self::hasValue($details['care_needs'] ?? null)) {
            $summary[] = sprintf(
                'Veterinary care needs: %s',
                self::formatValue($details['care_needs'], BookingRequestSchema::allVariantOptions('vet-care')),
            );
            $handledKeys[] = 'care_needs';
        }

        if (self::hasValue($details['message'] ?? null)) {
            $summary[] = 'Customer message: '.self::formatValue($details['message']);
            $handledKeys[] = 'message';
        }

        foreach ($details as $key => $value) {
            if (in_array((string) $key, $handledKeys, true) || ! self::hasValue($value)) {
                continue;
            }

            $summary[] = sprintf('%s: %s', Str::headline((string) $key), self::formatValue($value));
        }

        return $summary !== [] ? implode(' · ', $summary) : 'Not specified';
    }

    /**
     * @param  array<string, mixed>  $definition
     */
    private static function makeField(array $definition, string $pathPrefix = 'details'): Field
    {
        $key = (string) ($definition['key'] ?? '');
        $name = $pathPrefix !== '' ? $pathPrefix.'.'.$key : $key;
        $label = (string) ($definition['label'] ?? Str::headline($key));

        $field = match ($definition['type'] ?? 'text') {
            'date' => DatePicker::make($name),
            'select', 'country' => Select::make($name)
                ->options(is_array($definition['options'] ?? null) ? $definition['options'] : [])
                ->searchable(),
            'textarea' => Textarea::make($name)->rows(3),
            default => TextInput::make($name),
        };

        $field->label($label)
            ->required(self::requiredRule($definition))
            ->visible(self::visibilityRule($definition));

        if (filled($definition['help'] ?? null)) {
            $field->helperText((string) $definition['help']);
        }

        if ($field instanceof TextInput && filled($definition['placeholder'] ?? null)) {
            $field->placeholder((string) $definition['placeholder']);
        }

        if ($field instanceof Select && filled($definition['placeholder'] ?? null)) {
            $field->placeholder((string) $definition['placeholder']);
        }

        return $field;
    }

    /**
     * @param  array<string, mixed>  $definition
     */
    private static function requiredRule(array $definition): bool|\Closure
    {
        $requiredWhen = $definition['required_when'] ?? null;

        if (is_array($requiredWhen)) {
            return static fn (Get $get): bool => self::conditionMatches($get, $requiredWhen);
        }

        return (bool) ($definition['required'] ?? false);
    }

    /**
     * @param  array<string, mixed>  $definition
     */
    private static function visibilityRule(array $definition): bool|\Closure
    {
        $visibleWhen = $definition['visible_when'] ?? null;

        if (is_array($visibleWhen)) {
            return static fn (Get $get): bool => self::conditionMatches($get, $visibleWhen);
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $condition
     */
    private static function conditionMatches(Get $get, array $condition): bool
    {
        $key = (string) ($condition['key'] ?? '');
        $path = ($condition['scope'] ?? 'details') === 'service' ? $key : 'details.'.$key;
        $values = is_array($condition['values'] ?? null) ? $condition['values'] : [];

        return in_array($get($path), $values, true);
    }

    private static function formatAdditionalValue(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_scalar($value) || $value === null) {
            return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '';
        }

        return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '';
    }

    private static function decodeStructuredValue(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $value = trim($value);

        if ($value === '' || ! in_array(substr($value, 0, 1), ['{', '['], true)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    /**
     * @param  array<string, string>|null  $options
     */
    private static function formatValue(mixed $value, ?array $options = null): string
    {
        if (is_array($value)) {
            return collect($value)
                ->map(fn (mixed $item): string => self::formatValue($item, $options))
                ->filter()
                ->implode(', ');
        }

        if ($options !== null && is_string($value) && isset($options[$value])) {
            return $options[$value];
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '';
    }

    private static function hasValue(mixed $value): bool
    {
        return $value !== null && $value !== '' && $value !== [];
    }
}
