<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'q' => preg_replace('/\s+/u', ' ', trim(strip_tags((string) $this->input('q', '')))),
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:120'],
        ];
    }
}
