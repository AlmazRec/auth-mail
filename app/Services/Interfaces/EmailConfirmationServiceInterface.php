<?php

namespace App\Services\Interfaces;

interface EmailConfirmationServiceInterface
{
    public function confirmEmail(string $confirmationToken);
}
