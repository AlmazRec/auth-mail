<?php

namespace App\Services;

use App\Enums\EmailMessages;
use App\Enums\ErrorMessages;
use App\Models\EmailConfirmation;
use App\Services\Interfaces\EmailConfirmationInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EmailConfirmationService implements EmailConfirmationInterface
{
    public function confirmEmail(string $confirmationToken): JsonResponse
    {

        try {
            $userWithToken = EmailConfirmation::where('confirmation_token', $confirmationToken)->firstOrFail();

            $user = $userWithToken->user;

            if ($user->email_confirmed) {
                return response()->json([
                    'message' => EmailMessages::EMAIL_ALREADY_CONFIRM->value
                ]);
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

            return response()->json([
                'message' => ErrorMessages::INVALID_TOKEN->value
            ], 404);

        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => ErrorMessages::INTERNAL_SERVER_ERROR->value
            ], 500);

        }
    }
}
