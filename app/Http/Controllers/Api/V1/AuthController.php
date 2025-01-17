<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\Interfaces\EmailConfirmationInterface;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\TokenServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $user = $this->authService->signUp($request->validated());

        $this->emailService->sendWelcomeEmail($user->email, $user->name);

        return response()->json([
            'User' => $user,
            'Message' => 'Теперь вы можете авторизоваться на сайте.'
        ]);
    }


    public function signIn(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $token = $this->authService->signIn($credentials);

        return response()->json([
            'token' => $this->tokenService->respondWithToken($token),
            'message' => 'Вы успешно вошли.'
        ]);
    }


    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Вы успешно вышли.'
        ]);
    }


    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }

}
