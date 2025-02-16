<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\EmailMessages;
use App\Enums\ErrorMessages;
use App\Http\Controllers\Controller;
use App\Jobs\sendConfirmEmailJob;
use App\Repositories\EmailRepository;
use App\Repositories\Interfaces\EmailRepositoryInterface;
use App\Services\Interfaces\EmailConfirmationInterface;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\TokenServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    /**
     * DI-container
     */
    protected EmailConfirmationInterface $emailConfirmationService;
    protected EmailServiceInterface $emailService;
    protected TokenServiceInterface $tokenService;

    protected EmailRepository $emailRepository;

    public function __construct(EmailRepositoryInterface $emailRepository, EmailServiceInterface $emailService, TokenServiceInterface $tokenService, EmailConfirmationInterface $emailConfirmationService)
    {
        $this->emailService = $emailService;
        $this->tokenService = $tokenService;
        $this->emailRepository = $emailRepository;
        $this->emailConfirmationService = $emailConfirmationService;
    }


    public function confirmEmail(string $confirmationToken): JsonResponse
    {

        try {
            return $this->emailConfirmationService->confirmEmail($confirmationToken); // Подтверждение почты
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => ErrorMessages::INTERNAL_SERVER_ERROR->value
            ], 500);
        }

    }


    public function sendConfirmEmail(): JsonResponse
    {
        try {
            $confirmationToken = $this->tokenService->generateConfirmationToken();

            sendConfirmEmailJob::dispatch(auth('api')->user()->email, $confirmationToken, $this->emailService);

            $this->emailRepository->storeConfirmationToken($confirmationToken);

            return response()->json([
                'message' => EmailMessages::SUCCESS_SEND->value
            ]);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => $e
            ], 500);
        }
    }
}
