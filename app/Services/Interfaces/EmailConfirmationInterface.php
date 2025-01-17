<?php

namespace App\Services\Interfaces;

interface EmailConfirmationInterface
{
    public function confirmEmail(string $confirmationToken);
}
