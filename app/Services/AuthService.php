<?php

namespace App\Services;

use App\Enums\AuthMessages;
use App\Models\User;
use App\Services\Interfaces\AuthServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthService implements AuthServiceInterface {

    /**
     * @throws Exception
     */
    public function signUp(array $data): User
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
                throw new Exception(AuthMessages::INCORRECT_LOGIN_OR_PASSWORD->value);
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


    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }
}
