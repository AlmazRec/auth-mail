<?php

namespace App\Repositories;

use App\Exceptions\ConfirmationTokenStoreException;
use App\Exceptions\UserNotFoundException;
use App\Models\EmailConfirmation;
use App\Repositories\Interfaces\EmailRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class EmailRepository implements EmailRepositoryInterface
{
    public function storeConfirmationToken(string $confirmationToken): EmailConfirmation
    {
        try {
            return EmailConfirmation::create([
                'user_id' => auth('api')->user()->id,
                'confirmation_token' => $confirmationToken
            ]);
        } catch (QueryException $e) {
            Log::error($e);

            throw new ConfirmationTokenStoreException($e);
        }
    }

    public function findByConfirmationToken(string $confirmationToken): EmailConfirmation
    {
        try {
            return EmailConfirmation::where('confirmation_token', $confirmationToken)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException($e);
        }
    }

}
