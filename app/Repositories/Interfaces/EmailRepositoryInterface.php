<?php

namespace App\Repositories\Interfaces;

interface EmailRepositoryInterface
{
    public function storeConfirmationToken(string $confirmationToken);
}
