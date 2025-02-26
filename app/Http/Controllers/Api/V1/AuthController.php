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

/**
 * @OA\Info(
 *     title="Laravel Swagger Demo",
 *     description="Demonstration of Swagger with Laravel",
 *     version="1.0",
 *     @OA\Contact(
 *         email="hello@example.com"
 *     )
 * )
 *
 * @OA\Server(url="http://localhost:8000/api/v1")
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints of Auth"
 * )
 */
class AuthController extends Controller
{
    protected AuthServiceInterface $authService;
    protected EmailServiceInterface $emailService;
    protected TokenServiceInterface $tokenService;

    protected EmailConfirmationInterface $emailConfirmationService;

    /**
     * @OA\Constructor(
     *     @OA\Parameter(
     *         name="service_auth",
     *         parameter="service",
     *         description="Service of auth",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="service_email",
     *         description="Service of email",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="service_token",
     *         description="Service of token",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="service_email_confirmation",
     *         description="Service of confirmation",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     )
     * )
     */
    public function __construct(AuthServiceInterface $authService, EmailServiceInterface $emailService, TokenServiceInterface $tokenService, EmailConfirmationInterface $emailConfirmationService)
    {
        $this->authService = $authService;
        $this->emailService = $emailService;
        $this->tokenService = $tokenService;
        $this->emailConfirmationService = $emailConfirmationService;
    }


    /**
     * @OA\Post(
     *     path="/auth/sign-up",
     *     summary="Sign up",
     *     description="Sign up",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         description="User info",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="John Doe"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="user1@example.com"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="Passw0rd"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation exception"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
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


    /**
     * @OA\Post(
     *     path="/auth/sign-in",
     *     summary="Sign in",
     *     description="Sign in",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         description="User info",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="user1@example.com"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="Passw0rd"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation exception"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
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



    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Logout",
     *     description="Logout",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation exception"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
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


