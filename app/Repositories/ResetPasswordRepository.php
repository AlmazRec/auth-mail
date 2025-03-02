<?php

namespace App\Repositories;

use App\Exceptions\InternalServerErrorException;
use App\Models\PasswordReset;
use App\Models\User;
use App\Repositories\Interfaces\ResetPasswordRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class ResetPasswordRepository implements ResetPasswordRepositoryInterface
{

    public function storeResetToken(User $user, string $resetToken): PasswordReset
    {
        try {
            return PasswordReset::create([
                'user_id' => $user->id,
                'reset_token' => $resetToken
            ]);
        } catch (QueryException $e) {
            throw new InternalServerErrorException($e);
        }
    }

    public function findByResetToken(string $resetToken): PasswordReset
    {
        try {
            return PasswordReset::where('reset_token', $resetToken)->first();
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException($e);
        }
    }
}
