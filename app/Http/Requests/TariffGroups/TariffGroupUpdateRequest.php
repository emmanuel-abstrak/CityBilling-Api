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
            'residential_rates_charge' => ['required'],
            'residential_refuse_charge' => ['required'],
            'residential_sewerage_charge' => ['required'],
            'commercial_rates_charge' => ['required'],
            'commercial_refuse_charge' => ['required'],
            'commercial_sewerage_charge' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
