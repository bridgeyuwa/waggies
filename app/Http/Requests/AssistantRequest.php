<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class AssistantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'message' => preg_replace('/\s+/u', ' ', trim(strip_tags((string) $this->input('message', '')))),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:1200'],
            'history' => ['nullable', 'array', 'max:8'],
            'history.*.role' => ['required', 'string', Rule::in(['user', 'assistant'])],
            'history.*.content' => ['required', 'string', 'max:2000'],
        ];
    }
}
