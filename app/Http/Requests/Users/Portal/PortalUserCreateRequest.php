<?php

namespace App\Http\Requests\Users\Portal;

use App\Library\Enums\Gender;
use App\Library\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class PortalUserCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'in:' . implode(',', UserRole::values())],
            'gender' => ['required', 'string', 'in:' . implode(',', Gender::values())],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
