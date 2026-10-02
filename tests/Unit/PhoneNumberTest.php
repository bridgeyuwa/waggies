<?php

use App\Support\PhoneNumber;
use Tests\TestCase;

uses(TestCase::class);

it('normalizes Nigerian local numbers to international format', function (): void {
    expect(PhoneNumber::normalize('NG', '0808 081 1902'))->toBe('+2348080811902');
});

it('normalizes an international number for another configured country', function (): void {
    expect(PhoneNumber::normalize('GH', '024 123 4567'))->toBe('+233241234567');
});

it('accepts an arbitrary international calling code through the other option', function (): void {
    expect(PhoneNumber::normalize('OTHER', '202 555 0123', '+1'))->toBe('+12025550123');
});

it('rejects numbers that do not match the selected country length', function (): void {
    expect(PhoneNumber::normalize('NG', '0808 081 19'))->toBeNull();
});

it('offers Nigeria first and keeps an other-country escape hatch', function (): void {
    $options = PhoneNumber::countryOptions();

    expect(array_key_first($options))->toBe('NG')
        ->and($options)->toHaveKey('OTHER', 'Another country code');
});
