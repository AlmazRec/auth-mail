<?php

namespace App\Repositories\Interfaces;

use App\Models\EmailConfirmation;
use App\Models\User;

interface UserRepositoryInterface
{
    public function store(array $data): User;

    public function findByConfirmationToken(string $confirmationToken): EmailConfirmation;

    public function findByEmail(string $email): User;
}
