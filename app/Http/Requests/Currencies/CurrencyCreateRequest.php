<?php

namespace App\Http\Requests\Currencies;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:5'],
            'symbol' => ['required', 'string', 'max:5'],
            'exchange_rate' => ['required']
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
