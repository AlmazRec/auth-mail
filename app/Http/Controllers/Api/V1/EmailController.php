<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\EmailConfirmation;
use App\Services\Interfaces\EmailConfirmationInterface;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\TokenServiceInterface;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    protected EmailConfirmationInterface $emailConfirmationService;
    protected EmailServiceInterface $emailService;
    protected TokenServiceInterface $tokenService;
    public function __construct(EmailServiceInterface $emailService, TokenServiceInterface $tokenService, EmailConfirmationInterface $emailConfirmationService)
    {
        $this->emailService = $emailService;
        $this->tokenService = $tokenService;
        $this->emailConfirmationService = $emailConfirmationService;
    }


    public function confirmEmail(string $confirmationToken): JsonResponse
    {
        return $this->emailConfirmationService->confirmEmail($confirmationToken);
    }


    public function sendConfirmEmail(): JsonResponse
    {
        $confirmationToken = $this->tokenService->generateConfirmationToken();

        $this->emailService->sendConfirmEmail(auth('api')->user()->email, $confirmationToken);

        EmailConfirmation::create([
            'user_id' => auth('api')->user()->id,
            'confirmation_token' => $confirmationToken
        ]);

        return response()->json([
            'message' => 'Письмо с ссылкой на подтверждение отправлено.'
        ]);
    }
}
