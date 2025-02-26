<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\EmailMessages;
use App\Exceptions\ConfirmationTokenStoreException;
use App\Exceptions\EmailAlreadyConfirmedException;
use App\Exceptions\InternalServerErrorException;
use App\Http\Controllers\Controller;
use App\Jobs\sendConfirmEmailJob;
use App\Repositories\EmailRepository;
use App\Repositories\Interfaces\EmailRepositoryInterface;
use App\Services\Interfaces\EmailConfirmationInterface;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\TokenServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

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
        } catch (InternalServerErrorException $e) {
            return $e->render();
        } catch (ModelNotFoundException $e) {
            return $e->render();
        } catch (EmailAlreadyConfirmedException $e) {
            return $e->render();
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
        } catch (InternalServerErrorException $e) {
            return $e->render();
        } catch (ConfirmationTokenStoreException $e) {
            return $e->render();
        }
    }
}
