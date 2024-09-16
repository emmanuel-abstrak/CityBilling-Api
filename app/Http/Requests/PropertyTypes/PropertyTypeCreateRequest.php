<?php

namespace App\Http\Requests\PropertyTypes;

use Illuminate\Foundation\Http\FormRequest;

class PropertyTypeCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:property_types,name']
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Type already exists'
        ];
    }
}
