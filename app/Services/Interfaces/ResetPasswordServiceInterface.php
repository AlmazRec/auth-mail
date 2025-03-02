<?php

namespace App\Services\Interfaces;


interface ResetPasswordServiceInterface
{
    public function passwordResetRequest(string $email): bool;

    public function resetPassword(string $newPassword, string $resetToken): bool;
}
