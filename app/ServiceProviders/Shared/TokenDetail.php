<?php

namespace App\ServiceProviders\Shared;

class TokenDetail
{
    private string $token;

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
