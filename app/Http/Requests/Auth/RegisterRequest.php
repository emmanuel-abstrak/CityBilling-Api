<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !auth()->check();
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255|unique:users,email',
            'first_name' => ['required'],
            'last_name' => ['required'],
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'account already exists',
        ];
    }
}
