<?php

namespace App\Services;

use App\Enums\EmailMessages;
use App\Exceptions\EmailAlreadyConfirmedException;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\EmailConfirmationInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EmailConfirmationService implements EmailConfirmationInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function confirmEmail(string $confirmationToken): JsonResponse
    {

        try {
            $userWithToken = $this->userRepository->findByConfirmationToken($confirmationToken);

            $user = $userWithToken->user;

            if ($user->email_confirmed) {
                throw new EmailAlreadyConfirmedException();
            }

            $user->email_confirmed = true;
            $user->email_confirmed_at = Carbon::now();

            $user->save();

            $userWithToken->delete();

            return response()->json([
                "message" => EmailMessages::SUCCESS_CONFIRM->value
            ]);

        } catch (ModelNotFoundException $e) {
            Log::error($e);

            throw new UserNotFoundException($e);

        } catch (InternalServerErrorException $e) {
            Log::error($e);

            throw new InternalServerErrorException($e);
        }
    }
}
