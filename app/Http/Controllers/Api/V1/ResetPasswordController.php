<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\ResetPasswordServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    protected ResetPasswordServiceInterface $resetPasswordService;
    protected UserRepositoryInterface $userRepository;
    public function __construct(ResetPasswordServiceInterface $resetPasswordService, UserRepositoryInterface $userRepository)
    {
        $this->resetPasswordService = $resetPasswordService;
        $this->userRepository = $userRepository;
    }

    public function passwordResetRequest(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->resetPasswordService->passwordResetRequest($request->email);

            return response()->json([
                'message' => 'password reset request sent successfully'
            ]);
        } catch (UserNotFoundException $e) {
            return $e->render();
        } catch (InternalServerErrorException $e) {
            return $e->render();
        }
    }


    public function resetPassword(PasswordResetRequest $request, string $resetToken): JsonResponse
    {
        try {
            $this->resetPasswordService->resetPassword($request->newPassword, $resetToken);

            return response()->json([
                'message' => 'password reset successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return $e->render();
        }


    }
}
