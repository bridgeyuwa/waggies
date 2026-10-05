<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

final class BookingWhatsAppMessage
{
    public function __construct(
        private readonly BookingPricingCatalog $pricingCatalog,
        private readonly CountryCatalog $countryCatalog,
    ) {}

    public function genericUrl(string $whatsappUrl): string
    {
        return $this->urlWithMessage(
            $whatsappUrl,
            'Hello Waggies, I submitted a booking request and would like to continue the conversation.',
        );
    }

    /**
     * @param  array<string, mixed>  $contact
     * @param  array<int, array<string, mixed>>  $pets
     * @param  array<int, array<string, mixed>>  $services
     */
    public function bookingUrl(string $whatsappUrl, array $contact, array $pets, array $services): string
    {
        return $this->urlWithMessage($whatsappUrl, $this->message($contact, $pets, $services));
    }

    /**
     * @param  array<string, mixed>  $contact
     * @param  array<int, array<string, mixed>>  $pets
     * @param  array<int, array<string, mixed>>  $services
     */
    public function message(array $contact, array $pets, array $services): string
    {
        $lines = [
            'Hello Waggies, I submitted a booking request.',
            '',
            'Request summary:',
            'Name: '.($contact['name'] ?? 'Not provided'),
        ];

        foreach (array_values($services) as $serviceIndex => $service) {
            $assignedPets = $this->petsForService($service, $pets);
            $lines[] = '';
            $lines[] = 'Service '.($serviceIndex + 1).': '.$this->pricingCatalog->serviceSummary($service, $assignedPets);
            $lines[] = 'Schedule: '.$this->scheduleSummary($service);

            if (filled($service['requested_time'] ?? null)) {
                $lines[] = 'Time: '.trim((string) $service['requested_time']);
            }

            if (filled($service['location'] ?? null)) {
                $lines[] = 'Location: '.trim((string) $service['location']);
            }

            foreach ($this->serviceDetails($service) as $label => $value) {
                $lines[] = "{$label}: {$value}";
            }

            if ($assignedPets !== []) {
                $lines[] = 'Assigned pets: '.collect($assignedPets)
                    ->map(fn (array $pet): string => (string) ($pet['name'] ?? 'Unnamed pet'))
                    ->join(', ');
            }
        }

        $lines[] = '';
        $lines[] = 'Pets:';

        foreach (array_values($pets) as $pet) {
            $lines[] = '- '.$this->petSummary($pet);

            if (filled($pet['notes'] ?? null)) {
                $lines[] = '  Notes: '.trim((string) $pet['notes']);
            }

            $petDetails = is_array($pet['details'] ?? null) ? $pet['details'] : [];

            if (filled($petDetails['other_description'] ?? null)) {
                $lines[] = '  Other details: '.trim((string) $petDetails['other_description']);
            }
        }

        return implode("\n", $lines);
    }

    /**
     * @param  array<string, mixed>  $service
     * @param  array<int, array<string, mixed>>  $pets
     * @return array<int, array<string, mixed>>
     */
    private function petsForService(array $service, array $pets): array
    {
        return collect($service['assigned_pet_ids'] ?? [])
            ->map(fn (mixed $petIndex): ?array => is_array($pets[(int) $petIndex] ?? null) ? $pets[(int) $petIndex] : null)
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $service
     * @return array<string, string>
     */
    private function serviceDetails(array $service): array
    {
        $details = is_array($service['details'] ?? null) ? $service['details'] : [];
        $fields = BookingRequestSchema::serviceFields(
            (string) ($service['service_key'] ?? ''),
            is_string($service['service_variant'] ?? null) ? $service['service_variant'] : null,
        );
        $knownKeys = [];
        $result = [];

        foreach ($fields as $field) {
            $key = (string) ($field['key'] ?? '');
            $knownKeys[] = $key;
            $value = ($field['scope'] ?? 'details') === 'service'
                ? ($service[$key] ?? null)
                : ($details[$key] ?? null);
            $formatted = $this->formatFieldValue($value, $field);

            if ($formatted !== null) {
                $result[(string) ($field['label'] ?? Str::headline($key))] = $formatted;
            }
        }

        if (($service['service_key'] ?? null) === 'vet-care') {
            $careNeeds = $this->pricingCatalog->serviceSelectionSummary($service);

            if ($careNeeds !== null) {
                $result['Care requested'] = $careNeeds;
            }
        }

        foreach ($details as $key => $value) {
            if (in_array($key, $knownKeys, true) || $key === 'care_needs') {
                continue;
            }

            $formatted = $this->formatValue($value);

            if ($formatted !== null) {
                $result[Str::headline((string) $key)] = $formatted;
            }
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>  $field
     */
    private function formatFieldValue(mixed $value, array $field): ?string
    {
        if (($field['type'] ?? null) === 'date' && is_string($value)) {
            return $this->dateLabel($value);
        }

        if (($field['type'] ?? null) === 'country' && is_string($value)) {
            return $this->countryCatalog->label($value) ?? $value;
        }

        if (($field['type'] ?? null) === 'select' && is_string($value)) {
            $options = is_array($field['options'] ?? null) ? $field['options'] : [];

            return isset($options[$value]) ? (string) $options[$value] : $value;
        }

        return $this->formatValue($value);
    }

    private function formatValue(mixed $value): ?string
    {
        if (is_array($value)) {
            $values = collect($value)
                ->map(fn (mixed $item): ?string => $this->formatValue($item))
                ->filter()
                ->values()
                ->all();

            return $values === [] ? null : implode(', ', $values);
        }

        if (! is_scalar($value)) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /**
     * @param  array<string, mixed>  $pet
     */
    private function petSummary(array $pet): string
    {
        $attributes = array_filter([
            $this->petSpeciesLabel($pet['species'] ?? null),
            filled($pet['size'] ?? null) ? Str::headline((string) $pet['size']).' size' : null,
            $pet['breed'] ?? null,
            $this->pricingCatalog->petAgeOptions()[(string) ($pet['age'] ?? '')] ?? ($pet['age'] ?? null),
            filled($pet['sex'] ?? null) ? Str::headline((string) $pet['sex']) : null,
        ], static fn (mixed $value): bool => filled($value));

        return (string) ($pet['name'] ?? 'Unnamed pet').($attributes !== [] ? ' ('.implode(', ', $attributes).')' : '');
    }

    private function petSpeciesLabel(mixed $species): ?string
    {
        if (! is_string($species) || trim($species) === '') {
            return null;
        }

        return [
            'dog' => 'Dog',
            'cat' => 'Cat',
            'other' => 'Other pet',
        ][$species] ?? Str::headline($species);
    }

    /**
     * @param  array<string, mixed>  $service
     */
    private function scheduleSummary(array $service): string
    {
        $details = is_array($service['details'] ?? null) ? $service['details'] : [];
        $serviceKey = (string) ($service['service_key'] ?? '');
        $startDate = $serviceKey === 'boarding'
            ? ($details['check_in'] ?? $service['requested_date'] ?? null)
            : ($service['requested_date'] ?? null);
        $dateLabel = $this->dateLabel($startDate);

        if ($serviceKey === 'boarding' && filled($details['check_out'] ?? null)) {
            $endDateLabel = $this->dateLabel($details['check_out']);

            if ($dateLabel !== null && $endDateLabel !== null) {
                try {
                    $nights = Carbon::parse((string) $details['check_in'])->diffInDays(Carbon::parse((string) $details['check_out']));
                    $dateLabel .= " → {$endDateLabel} · {$nights} night".($nights === 1 ? '' : 's');
                } catch (\Throwable) {
                    $dateLabel .= " → {$endDateLabel}";
                }
            }
        }

        if ($serviceKey === 'relocation') {
            $dateLabel = match ($details['travel_timing'] ?? null) {
                'exact' => $dateLabel,
                'window' => $dateLabel !== null && filled($service['requested_end_date'] ?? null)
                    ? $dateLabel.' → '.$this->dateLabel($service['requested_end_date'])
                    : $dateLabel,
                'not_decided' => 'Timing not decided',
                default => null,
            };

            return implode(' · ', array_filter([
                $dateLabel ?? 'Travel timing not added yet',
                $this->countryCatalog->label($details['origin_country'] ?? null),
                $this->countryCatalog->label($details['destination_country'] ?? null),
            ]));
        }

        return $dateLabel ?? 'Date not added yet';
    }

    private function dateLabel(mixed $date): ?string
    {
        if (! is_string($date) || trim($date) === '') {
            return null;
        }

        try {
            return Carbon::parse($date)->format('D, M j, Y');
        } catch (\Throwable) {
            return trim($date);
        }
    }

    private function urlWithMessage(string $whatsappUrl, string $message): string
    {
        $parts = parse_url($whatsappUrl);

        if (! is_array($parts) || ! isset($parts['scheme'], $parts['host'])) {
            return $whatsappUrl;
        }

        $query = [];
        parse_str($parts['query'] ?? '', $query);
        $query['text'] = $message;
        $parts['query'] = http_build_query($query, '', '&', PHP_QUERY_RFC3986);

        return $this->unparseUrl($parts);
    }

    /**
     * @param  array<string, mixed>  $parts
     */
    private function unparseUrl(array $parts): string
    {
        $url = isset($parts['scheme']) ? $parts['scheme'].'://' : '';
        $url .= $parts['user'] ?? '';
        $url .= isset($parts['pass']) ? ':'.$parts['pass'] : '';
        $url .= isset($parts['user']) ? '@' : '';
        $url .= $parts['host'] ?? '';
        $url .= isset($parts['port']) ? ':'.$parts['port'] : '';
        $url .= $parts['path'] ?? '';
        $url .= filled($parts['query'] ?? null) ? '?'.$parts['query'] : '';
        $url .= filled($parts['fragment'] ?? null) ? '#'.$parts['fragment'] : '';

        return $url;
    }
}
