<?php

namespace App\Http\Requests\Suburbs;

use Illuminate\Foundation\Http\FormRequest;

class SuburbCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:suburbs,name']
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Suburb already exists'
        ];
    }
}
