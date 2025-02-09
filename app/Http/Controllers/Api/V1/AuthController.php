<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\AuthMessages;
use App\Enums\ErrorMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\Interfaces\EmailConfirmationInterface;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\TokenServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;
    protected EmailServiceInterface $emailService;
    protected TokenServiceInterface $tokenService;

    protected EmailConfirmationInterface $emailConfirmationService;

    public function __construct(AuthServiceInterface $authService, EmailServiceInterface $emailService, TokenServiceInterface $tokenService, EmailConfirmationInterface $emailConfirmationService)
    {
        $this->authService = $authService;
        $this->emailService = $emailService;
        $this->tokenService = $tokenService;
        $this->emailConfirmationService = $emailConfirmationService;
    }


    public function signUp(SignUpRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->signUp($request->validated());
            $this->emailService->sendWelcomeEmail($user->email, $user->name);

            return response()->json([
                'User' => $user,
                'Message' => AuthMessages::SUCCESS_REGISTER->value
            ]);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => ErrorMessages::INTERNAL_SERVER_ERROR
            ]);
        }
    }


    public function signIn(SignInRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            $token = $this->authService->signIn($credentials);
            return response()->json([
                'token' => $token,
                'message' => AuthMessages::SUCCESS_JOIN->value
            ]);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => 'Неверный логин или пароль.'
            ], 401);
        }
    }



    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => AuthMessages::SUCCESS_EXIT->value
        ]);
    }


    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }

}
