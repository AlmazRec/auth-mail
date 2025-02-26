<?php

namespace App\Services;

use App\Enums\AuthMessages;
use App\Exceptions\IncorrectCredentialsException;
use App\Exceptions\InternalServerErrorException;
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
        } catch (Exception $e) { // Обработка других исключений
            Log::error($e);
            throw new InternalServerErrorException("Общая ошибка сервиса.", 0, $e);
        }
    }

    /**
     * @throws Exception
     */

    public function signIn(array $credentials): string
    {
        try {
            if (!$token = auth('api')->attempt($credentials)) {
                throw new IncorrectCredentialsException(AuthMessages::INCORRECT_CREDENTIALS->value);
            }
            return $token;
        } catch (InternalServerErrorException $e) {
            Log::error($e);

            throw new InternalServerErrorException($e);
        }
    }


    public function logout(): bool
    {
        try {
            return auth('api')->logout();
        } catch (InternalServerErrorException $e) {
            Log::error($e);

            throw new InternalServerErrorException($e);
        }
    }
}
