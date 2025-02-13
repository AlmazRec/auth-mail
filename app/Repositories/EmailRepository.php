<?php

namespace App\Repositories;

use App\Models\EmailConfirmation;
use App\Repositories\Interfaces\EmailRepositoryInterface;

class EmailRepository implements EmailRepositoryInterface
{
    public function storeConfirmationToken(string $confirmationToken): EmailConfirmation
    {
        return EmailConfirmation::create([
            'user_id' => auth('api')->user()->id,
            'confirmation_token' => $confirmationToken
        ]);
    }
}
