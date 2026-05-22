<?php

namespace App\Http\Requests;

use App\Support\PricingQuote;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('service')) {
            return;
        }

        $resolved = PricingQuote::resolveServiceContext(
            $this->string('service')->toString(),
            $this->input('variant'),
        );

        $this->merge([
            'service' => $resolved['service'] ?: $this->input('service'),
            'variant' => $resolved['variant'] ?? $this->input('variant'),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $services = array_keys(config('waggies.pricing.services', []));

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'service' => ['nullable', 'string', Rule::in($services)],
            'variant' => ['nullable', 'string', 'max:50'],
            'tier' => ['nullable', 'string', 'max:50'],
            'intent' => ['nullable', 'string', Rule::in(['book', 'save', 'consult'])],
            'estimate_summary' => ['nullable', 'string', 'max:500'],
        ];
    }
}
