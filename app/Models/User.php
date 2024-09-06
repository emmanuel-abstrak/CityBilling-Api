<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as CanBeAuthenticated;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;

class User extends CanBeAuthenticated
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'user_type',
        'first_name',
        'last_name',
        'phone_number',
        'id_number',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function createToken(): NewAccessToken
    {
        $token = $this->tokens()->create([
            'name' => 'access_token',
            'token' => hash('sha256', $plainTextToken = Str::random(240)),
            'abilities' => ['*'],
        ]);

        return new NewAccessToken($token, $plainTextToken);
    }
}
