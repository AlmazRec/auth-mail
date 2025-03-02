<?php

namespace App\Repositories;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\UserNotFoundException;
use App\Models\EmailConfirmation;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class UserRepository implements UserRepositoryInterface
{
    public function store(array $data): User
    {
        try {
            return User::create($data);
        } catch (QueryException $e) {
            throw new InternalServerErrorException($e);
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

    public function findByEmail(string $email): User
    {
        try {
            return User::where('email', $email)->first();
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException($e);
        }
    }
}
