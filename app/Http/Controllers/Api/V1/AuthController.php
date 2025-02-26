<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\AuthMessages;
use App\Exceptions\InternalServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Jobs\SendWelcomeEmail;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\Interfaces\EmailConfirmationInterface;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\TokenServiceInterface;
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
            SendWelcomeEmail::dispatch($user->email, $user->name, $this->emailService); // Создание очереди для отправки приветственного письма

            return response()->json([
                'User' => $user,
                'Message' => AuthMessages::SUCCESS_REGISTER->value
            ],201);
        } catch (InternalServerErrorException $e) {
            Log::error($e);

            return $e->render();
        }
    }


    public function signIn(SignInRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');
            $token = $this->authService->signIn($credentials); // Проверяем введенные данные

            return response()->json([
                'token' => $token,
                'message' => AuthMessages::SUCCESS_JOIN->value
            ]);
        } catch (InternalServerErrorException $e) {
            Log::error($e);

            return $e->render();
        }
    }



    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();

            return response()->json([
                'message' => AuthMessages::SUCCESS_EXIT->value
            ]);
        } catch (InternalServerErrorException $e) {
            Log::error($e);

            return $e->render();
        }

    }
}
