<?php

namespace App\Services;

use App\Models\User;
use App\Services\Interfaces\AuthServiceInterface;

class AuthService implements AuthServiceInterface {

    public function signUp(array $data)
    {
        return User::create($data);
    }

    public function signIn(array $credentials): string
    {
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Неверный email или пароль.'
            ]);
        }

        return $token;
    }


    public function logout(): null
    {
        return auth('api')->logout();
    }
}
