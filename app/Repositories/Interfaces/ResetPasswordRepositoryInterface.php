<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface ResetPasswordRepositoryInterface
{
    public function storeResetToken(User $user, string $resetToken);
}
