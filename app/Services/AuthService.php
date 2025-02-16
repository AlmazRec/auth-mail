<?php

namespace App\Services;

use App\Enums\AuthMessages;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthService implements AuthServiceInterface
{
    protected UserRepositoryInterface $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws Exception
     */
    public function signUp(array $data): User
    {
        try {
            return $this->userRepository->store($data);
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


    public function logout(): bool
    {
        return auth('api')->logout();
    }
}
