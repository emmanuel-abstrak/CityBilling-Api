<?php

namespace App\Http\Requests\Properties;

use App\Library\Enums\PropertyType;
use Illuminate\Foundation\Http\FormRequest;

class PropertyUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'suburb_id' => ['required', 'exists:suburbs,id'],
            'type' => ['required', 'in:'.implode(',', PropertyType::values())],
            'size' => ['required'],
            'meter' => ['required'],
            'address' => ['required'],
            'email' => ['required', 'email'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'id_number' => ['required'],
            'phone_number' => ['required'],
            'send_notification' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
