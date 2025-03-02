<?php

namespace App\Services;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\ResetPasswordRepository;
use App\Services\Interfaces\ResetPasswordServiceInterface;
use App\Services\Interfaces\TokenServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResetPasswordService implements ResetPasswordServiceInterface
{
    protected UserRepositoryInterface $userRepository;
    protected ResetPasswordRepository $resetPasswordRepository;
    protected TokenServiceInterface $tokenService;
    public function __construct(UserRepositoryInterface $userRepository, ResetPasswordRepository $resetPasswordRepository, TokenServiceInterface $tokenService)
    {
        $this->userRepository = $userRepository;
        $this->resetPasswordRepository = $resetPasswordRepository;
        $this->tokenService = $tokenService;
    }

    /**
     * @throws UserNotFoundException
     * @throws InternalServerErrorException
     */
    public function passwordResetRequest(string $email): bool
    {
        try {
            $resetToken = $this->tokenService->generateConfirmationToken();
            $user = $this->userRepository->findByEmail($email);
            $this->resetPasswordRepository->storeResetToken($user, $resetToken);

            return true;
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException($e);
        } catch (InternalServerErrorException $e) {
            throw new InternalServerErrorException($e);
        }
    }

    public function resetPassword(string $newPassword, string $resetToken): bool
    {
        try {
            $passwordReset = $this->resetPasswordRepository->findByResetToken($resetToken);
            $user = $passwordReset->user;

            $user->password = $newPassword;

            $user->save();

            return true;
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException($e);
        }
    }
}
