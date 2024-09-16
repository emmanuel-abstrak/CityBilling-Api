<?php

namespace App\Http\Requests\Vending;

use Illuminate\Foundation\Http\FormRequest;

class MeterLookupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'meter' => ['required', 'string'],
            'amount' => ['required'],
            'currency' => ['required', 'exists:currencies,code'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
