<?php

namespace App\Services;

use App\Enums\ErrorMessages;
use App\Mail\ConfirmMail;
use App\Mail\WelcomeMail;
use App\Services\Interfaces\EmailServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService implements EmailServiceInterface
{

    public function sendWelcomeEmail(string $email, string $name): JsonResponse|SentMessage|null
    {
        try {
            return Mail::to($email)->send(new WelcomeMail($name));
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => ErrorMessages::INTERNAL_SERVER_ERROR
            ]);
        }
    }

    public function sendConfirmEmail(string $email, string $confirmationToken): JsonResponse|SentMessage|null
    {
        try {
            return Mail::to($email)->send(new ConfirmMail($confirmationToken));
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => ErrorMessages::INTERNAL_SERVER_ERROR
            ]);
        }
    }
}
