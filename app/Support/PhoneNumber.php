<?php

namespace App\Support;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

final class PhoneNumber
{
    /**
     * @return array<string, string>
     */
    public static function countryOptions(): array
    {
        $options = [];

        foreach (config('waggies_phone.countries', []) as $countryCode => $country) {
            if (! is_array($country) || ! is_string($country['name'] ?? null) || ! is_string($country['dialing_code'] ?? null)) {
                continue;
            }

            $options[$countryCode] = $country['name'].' (+'.$country['dialing_code'].')';
        }

        $options['OTHER'] = 'Another country code';

        return $options;
    }

    public static function defaultCountryCode(): string
    {
        return (string) config('waggies_phone.default_country', 'NG');
    }

    public static function normalizeContact(array $contact): ?string
    {
        $countryCode = (string) ($contact['phone_country'] ?? self::defaultCountryCode());
        $phoneNumber = $contact['phone_number'] ?? $contact['phone'] ?? null;

        return self::normalize(
            $countryCode,
            is_scalar($phoneNumber) ? (string) $phoneNumber : null,
            is_scalar($contact['phone_other_country_code'] ?? null)
                ? (string) $contact['phone_other_country_code']
                : null,
        );
    }

    public static function normalize(?string $countryCode, ?string $phoneNumber, ?string $otherCountryCode = null): ?string
    {
        $countryCode = strtoupper(trim((string) $countryCode));
        $countries = config('waggies_phone.countries', []);
        $country = is_array($countries[$countryCode] ?? null) ? $countries[$countryCode] : null;
        $dialingCode = $country['dialing_code'] ?? ($countryCode === 'OTHER' ? $otherCountryCode : null);
        $phoneNumber = trim((string) $phoneNumber);

        if ($phoneNumber === '' || ! is_string($dialingCode)) {
            return null;
        }

        $dialingCode = preg_replace('/\D+/', '', $dialingCode);

        if (! is_string($dialingCode) || $dialingCode === '' || strlen($dialingCode) > 3) {
            return null;
        }

        $input = $phoneNumber;
        $region = $countryCode === 'OTHER' ? null : $countryCode;

        if ($countryCode === 'OTHER' || str_starts_with($phoneNumber, '+') || str_starts_with($phoneNumber, '00')) {
            $digits = preg_replace('/\D+/', '', $phoneNumber);

            if (! is_string($digits) || $digits === '') {
                return null;
            }

            $input = '+'.(str_starts_with($phoneNumber, '00')
                ? substr($digits, 2)
                : ($countryCode === 'OTHER' && ! str_starts_with($phoneNumber, '+')
                    ? $dialingCode.$digits
                    : $digits));
            $region = null;
        }

        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $number = $phoneUtil->parse($input, $region);

            return $phoneUtil->isValidNumber($number)
                ? $phoneUtil->format($number, PhoneNumberFormat::E164)
                : null;
        } catch (NumberParseException) {
            return null;
        }
    }
}
