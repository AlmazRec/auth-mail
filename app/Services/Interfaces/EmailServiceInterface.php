<?php

namespace App\Services\Interfaces;

interface EmailServiceInterface
{
    public function sendWelcomeEmail(string $email, string $name);

    public function sendConfirmEmail(string $email, string $confirmationToken);
}
