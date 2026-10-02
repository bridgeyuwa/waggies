<?php

namespace App\Http\Requests;

use App\Models\BookingRequest;
use App\Rules\ValidPhoneNumber;
use App\Support\PhoneNumber;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $idempotencyKey = $this->filled('idempotency_key')
            ? trim((string) $this->input('idempotency_key'))
            : trim((string) $this->header('Idempotency-Key'));
        $phoneCountry = $this->filled('phone_country')
            ? trim((string) $this->input('phone_country'))
            : PhoneNumber::defaultCountryCode();
        $phoneNumber = $this->filled('phone_number')
            ? trim((string) $this->input('phone_number'))
            : ($this->filled('phone') ? trim((string) $this->input('phone')) : null);
        $phone = PhoneNumber::normalize(
            $phoneCountry,
            $phoneNumber,
            $this->filled('phone_other_country_code') ? trim((string) $this->input('phone_other_country_code')) : null,
        );

        $this->merge([
            'idempotency_key' => $idempotencyKey !== '' ? $idempotencyKey : null,
            'name' => $this->filled('name') ? trim((string) $this->input('name')) : null,
            'email' => $this->filled('email') ? Str::lower(trim((string) $this->input('email'))) : null,
            'phone' => $phone,
            'phone_country' => $phoneCountry,
            'phone_number' => $phoneNumber,
            'phone_other_country_code' => $this->filled('phone_other_country_code') ? trim((string) $this->input('phone_other_country_code')) : null,
            'preferred_contact_method' => $this->filled('preferred_contact_method') ? trim((string) $this->input('preferred_contact_method')) : null,
            'service_key' => $this->filled('service_key') ? trim((string) $this->input('service_key')) : null,
            'requested_date' => $this->filled('requested_date') ? trim((string) $this->input('requested_date')) : null,
            'requested_time' => $this->filled('requested_time') ? trim((string) $this->input('requested_time')) : null,
            'pet_name' => $this->filled('pet_name') ? trim((string) $this->input('pet_name')) : null,
            'pet_type' => $this->filled('pet_type') ? trim((string) $this->input('pet_type')) : null,
            'location' => $this->filled('location') ? trim((string) $this->input('location')) : null,
            'message' => $this->filled('message') ? trim((string) $this->input('message')) : null,
            'source' => $this->filled('source') ? trim((string) $this->input('source')) : null,
            'service_variant' => $this->filled('service_variant') ? trim((string) $this->input('service_variant')) : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'idempotency_key' => ['nullable', 'string', 'max:64', 'regex:/^[A-Za-z0-9._:-]+$/'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:16'],
            'phone_country' => ['required', 'string', Rule::in(array_keys(PhoneNumber::countryOptions()))],
            'phone_other_country_code' => ['nullable', 'required_if:phone_country,OTHER', 'regex:/^\+?[0-9]{1,3}$/'],
            'phone_number' => [
                'required',
                'string',
                'max:40',
                new ValidPhoneNumber(
                    $this->input('phone_country'),
                    $this->input('phone_other_country_code'),
                ),
            ],
            'preferred_contact_method' => ['nullable', 'string', Rule::in(['phone', 'email', 'whatsapp'])],
            'service_key' => ['required', 'string', Rule::in(array_keys(BookingRequest::serviceOptions()))],
            'requested_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'requested_time' => ['nullable', 'date_format:H:i'],
            'pet_name' => ['required', 'string', 'max:80'],
            'pet_type' => ['required', 'string', Rule::in(['dog', 'cat'])],
            'location' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
            'service_variant' => ['nullable', 'string', 'max:80'],
            'pricing_tier' => ['prohibited'],
            'source' => ['nullable', 'string', 'max:120'],
            'website' => ['nullable', 'max:0'],
        ];
    }
}
