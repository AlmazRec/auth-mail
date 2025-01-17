<?php

namespace App\Services\Interfaces;

interface TokenServiceInterface
{
    public function respondWithToken($token);

    public function generateConfirmationToken();
}
