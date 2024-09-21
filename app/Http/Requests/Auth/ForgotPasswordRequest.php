<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !auth()->check();
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255|exists:users,email'
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'Account not found',
        ];
    }
}
