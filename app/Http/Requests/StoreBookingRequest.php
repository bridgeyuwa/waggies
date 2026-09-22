<?php

namespace App\Http\Requests;

use App\Models\BookingRequest;
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
        $this->merge([
            'name' => $this->filled('name') ? trim((string) $this->input('name')) : null,
            'email' => $this->filled('email') ? Str::lower(trim((string) $this->input('email'))) : null,
            'phone' => $this->filled('phone') ? trim((string) $this->input('phone')) : null,
            'service_key' => $this->filled('service_key') ? trim((string) $this->input('service_key')) : null,
            'requested_date' => $this->filled('requested_date') ? trim((string) $this->input('requested_date')) : null,
            'requested_time' => $this->filled('requested_time') ? trim((string) $this->input('requested_time')) : null,
            'pet_name' => $this->filled('pet_name') ? trim((string) $this->input('pet_name')) : null,
            'pet_type' => $this->filled('pet_type') ? trim((string) $this->input('pet_type')) : null,
            'location' => $this->filled('location') ? trim((string) $this->input('location')) : null,
            'message' => $this->filled('message') ? trim((string) $this->input('message')) : null,
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
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'service_key' => ['required', 'string', Rule::in(array_keys(BookingRequest::serviceOptions()))],
            'requested_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'requested_time' => ['nullable', 'date_format:H:i'],
            'pet_name' => ['required', 'string', 'max:80'],
            'pet_type' => ['required', 'string', Rule::in(['dog', 'cat', 'bird', 'rabbit', 'reptile', 'other'])],
            'location' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
            'website' => ['nullable', 'max:0'],
        ];
    }
}
