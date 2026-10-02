<?php

namespace App\Rules;

use App\Support\PhoneNumber;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

final class ValidPhoneNumber implements ValidationRule
{
    public function __construct(
        private readonly ?string $countryCode,
        private readonly ?string $otherCountryCode = null,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! filled($value)) {
            return;
        }

        if (PhoneNumber::normalize($this->countryCode, is_scalar($value) ? (string) $value : null, $this->otherCountryCode) !== null) {
            return;
        }

        $fail('Enter a valid phone number for the selected country.');
    }
}
