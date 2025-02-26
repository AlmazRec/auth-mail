<?php

namespace App\Repositories;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\UserNotFoundException;
use App\Models\EmailConfirmation;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{
    public function store(array $data): User
    {
        try {
            return User::create($data);
        } catch (QueryException $e) {
            Log::error($e);

            throw new InternalServerErrorException($e);
        }
    }

    public function findByConfirmationToken(string $confirmationToken): EmailConfirmation
    {
        try {
            return EmailConfirmation::where('confirmation_token', $confirmationToken)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Log::error($e);

            throw new UserNotFoundException($e);
        }
    }
}
