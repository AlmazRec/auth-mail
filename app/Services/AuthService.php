<?php

namespace App\Services;

use App\Enums\ErrorMessages;
use App\Models\User;
use App\Services\Interfaces\AuthServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService implements AuthServiceInterface {

    /**
     * @throws Exception
     */
    public function signUp(array $data): User|JsonResponse
    {
        try {
            return User::create($data);
        } catch (Exception $e) {
            Log::error($e);

            throw new Exception($e);
        }
    }

    /**
     * @throws Exception
     */
    public function signIn(array $credentials): string
    {
        try {
            if (!$token = auth('api')->attempt($credentials)) {
                throw new Exception(ErrorMessages::INCORRECT_LOGIN_OR_PASSWORD->value);
            }
            return $token;
        } catch (Exception $e) {
            Log::error($e);

            throw new Exception($e);
        }
    }


    public function logout(): null
    {
        return auth('api')->logout();
    }
}
