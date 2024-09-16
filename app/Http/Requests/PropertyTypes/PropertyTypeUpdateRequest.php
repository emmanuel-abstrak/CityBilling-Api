<?php

namespace App\Http\Requests\PropertyTypes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropertyTypeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('property_types', 'name')->ignore($this->property_type)]
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Type already exists'
        ];
    }
}
