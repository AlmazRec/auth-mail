<?php

namespace App\Services;


use App\Services\Interfaces\TokenServiceInterface;
use Illuminate\Support\Str;

class TokenService implements TokenServiceInterface
{

    public function generateConfirmationToken(): string
    {
        return Str::random(60);
    }

    public function generateResetToken(): string
    {
        return Str::random(60);
    }
}
