<?php

namespace App\Http\Requests\TariffGroups;

use Illuminate\Foundation\Http\FormRequest;

class TariffGroupCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'suburb_id' => ['required', 'integer', 'exists:suburbs,id'],
            'min_size' => ['required'],
            'max_size' => ['required'],
            'tariffs' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
