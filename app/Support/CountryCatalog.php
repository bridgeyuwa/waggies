<?php

namespace App\Support;

use Locale;

final class CountryCatalog
{
    /**
     * @return array<string, string>
     */
    public function options(?string $exceptCode = null): array
    {
        $options = [];

        foreach ($this->countries() as $country) {
            if ($exceptCode !== null && $country['code'] === strtoupper($exceptCode)) {
                continue;
            }

            $options[$country['code']] = $country['name'];
        }

        return $options;
    }

    public function label(?string $code): ?string
    {
        $normalizedCode = strtoupper((string) $code);

        return $normalizedCode !== '' ? ($this->options()[$normalizedCode] ?? null) : null;
    }

    /**
     * @return list<array{code: string, name: string, aliases: list<string> }>
     */
    public function countries(): array
    {
        $config = config('waggies_countries', []);
        $locale = (string) ($config['locale'] ?? 'en');
        $labels = is_array($config['labels'] ?? null) ? $config['labels'] : [];
        $aliases = is_array($config['aliases'] ?? null) ? $config['aliases'] : [];

        $countries = collect($config['codes'] ?? [])
            ->map(function (mixed $code) use ($locale, $labels, $aliases): ?array {
                if (! is_string($code) || ! preg_match('/^[A-Z]{2}$/', $code)) {
                    return null;
                }

                $name = $labels[$code] ?? Locale::getDisplayRegion('und_'.$code, $locale);

                if (! is_string($name) || $name === '' || $name === $code) {
                    return null;
                }

                return [
                    'code' => $code,
                    'name' => $name,
                    'aliases' => array_values(array_filter($aliases[$code] ?? [], 'is_string')),
                ];
            })
            ->filter()
            ->sortBy(fn (array $country): string => $country['name'])
            ->values()
            ->all();

        return $countries;
    }
}
