<?php

namespace App\Services;


use App\Models\EmailConfirmation;
use App\Services\Interfaces\TokenServiceInterface;
use Illuminate\Support\Str;
use League\Flysystem\Config;

class TokenService implements TokenServiceInterface
{
    public function respondWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => (new Config())->get('jwt.ttl') * 60,
        ];
    }

    public function generateConfirmationToken(): string
    {
        return Str::random(60);
    }

    public function checkConfirmationToken($confirmationToken)
    {
        $userWithThisToken = EmailConfirmation::where('confirmation_token', $confirmationToken)->first();

        if ($userWithThisToken == null) {
            throw new \Exception('Неверный токен.', 404);
        }

        return $userWithThisToken;
    }
}
