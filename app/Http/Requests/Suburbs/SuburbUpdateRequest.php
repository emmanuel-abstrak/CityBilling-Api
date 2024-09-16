<?php

namespace App\Http\Requests\Suburbs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SuburbUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('suburbs', 'name')->ignore($this->suburb)]
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Suburb already exists'
        ];
    }
}
