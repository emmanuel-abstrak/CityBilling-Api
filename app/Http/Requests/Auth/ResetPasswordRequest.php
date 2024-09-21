<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !auth()->check();
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string',
            'code' => 'required|string|min:6|max:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'Account not found',
        ];
    }
}
