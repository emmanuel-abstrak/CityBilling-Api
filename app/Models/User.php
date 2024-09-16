<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as CanBeAuthenticated;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;

class User extends CanBeAuthenticated
{
    use HasFactory, Notifiable, HasApiTokens, TracksActivity;

    protected $fillable = [
        'role',
        'gender',
        'first_name',
        'last_name',
        'phone_number',
        'id_number',
        'email',
        'password'
    ];

    protected $appends = ['initials'];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getInitialsAttribute(): string
    {
        return sprintf(
            "%s%s",
            $this->getAttribute('first_name')[0],
            $this->getAttribute('last_name')[0]
        );
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

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'owner_id');
    }
}
