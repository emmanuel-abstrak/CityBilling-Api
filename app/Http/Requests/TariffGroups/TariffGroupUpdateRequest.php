<?php

namespace App\Http\Requests\TariffGroups;

use Illuminate\Foundation\Http\FormRequest;

class TariffGroupUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
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
