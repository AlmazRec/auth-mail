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
use App\Services\Interfaces\EmailConfirmationServiceInterface;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\TokenServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Email",
 *     description="API Endpoints of Email",
 *     @OA\ExternalDocumentation(
 *         description="Find out more",
 *         url="http://swagger.io"
 *     )
 * )
 *
 * @OA\Tag(
 *      name="Auth",
 *      description="API Endpoints of Auth"
 *  )
 * /
 */
class EmailController extends Controller
{
    /**
     * DI-container
     */
    protected EmailConfirmationServiceInterface $emailConfirmationService;
    protected EmailServiceInterface $emailService;
    protected TokenServiceInterface $tokenService;

    protected EmailRepository $emailRepository;

    public function __construct(EmailRepositoryInterface $emailRepository, EmailServiceInterface $emailService, TokenServiceInterface $tokenService, EmailConfirmationServiceInterface $emailConfirmationService)
    {
        $this->emailService = $emailService;
        $this->tokenService = $tokenService;
        $this->emailRepository = $emailRepository;
        $this->emailConfirmationService = $emailConfirmationService;
    }

    /**
     * @OA\Get(
     *     path="/v1/confirm-email/{confirmationToken}",
     *     summary="Confirm email",
     *     @OA\Parameter(
     *         description="Confirm token",
     *         in="path",
     *         name="confirmationToken",
     *         required=true,
     *         example="1234567890",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Email confirmed successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Invalid confirm token"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Something went wrong"
     *             )
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/v1/send-confirm-email",
     *     summary="Send confirm email",
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Email sent successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Invalid confirm token"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Something went wrong"
     *             )
     *         )
     *     )
     * )
     */
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
