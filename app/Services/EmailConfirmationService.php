<?php

namespace App\Services;

use App\Models\EmailConfirmation;
use App\Services\Interfaces\EmailConfirmationInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class EmailConfirmationService implements EmailConfirmationInterface
{
    public function confirmEmail(string $confirmationToken): JsonResponse
    {
        $userWithThisToken = EmailConfirmation::where('confirmation_token', $confirmationToken)->first();

        if (!$userWithThisToken) {
            return response()->json([
                "message" => 'Пользователь или токен не найден.'
            ]);
        }
        $user = $userWithThisToken->user;

        if ($user->email_confirmed == 1) {
            return response()->json([
                'message' => 'Почта уже подтверждена.'
            ]);
        }

        $user->email_confirmed = true;
        $user->email_confirmed_at = Carbon::now();

        $user->save();

        $userWithThisToken->delete();

        return response()->json([
            "message" => 'Почта успешно подтверждена.'
        ]);
    }
}
