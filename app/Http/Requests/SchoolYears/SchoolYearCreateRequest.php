<?php

namespace App\Http\Requests\SchoolYears;

use Illuminate\Foundation\Http\FormRequest;

class SchoolYearCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'is_current' => ['nullable', 'boolean'],
            'resting_days' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
