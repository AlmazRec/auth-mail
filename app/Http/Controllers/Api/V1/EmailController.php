<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\EmailMessages;
use App\Enums\ErrorMessages;
use App\Http\Controllers\Controller;
use App\Models\EmailConfirmation;
use App\Services\Interfaces\EmailConfirmationInterface;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\TokenServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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
        try {
            return $this->emailConfirmationService->confirmEmail($confirmationToken);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => ErrorMessages::INTERNAL_SERVER_ERROR->value
            ], 500);
        }
    }


    public function sendConfirmEmail(): JsonResponse
    {
        $confirmationToken = $this->tokenService->generateConfirmationToken();

        try {
            $this->emailService->sendConfirmEmail(auth('api')->user()->email, $confirmationToken);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => ErrorMessages::INTERNAL_SERVER_ERROR->value
            ], 500);
        }

        try {
            EmailConfirmation::create([
                'user_id' => auth('api')->user()->id,
                'confirmation_token' => $confirmationToken
            ]);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => ErrorMessages::INTERNAL_SERVER_ERROR->value
            ]);
        }


        return response()->json([
            'message' => EmailMessages::SUCCESS_SEND->value
        ]);
    }
}
