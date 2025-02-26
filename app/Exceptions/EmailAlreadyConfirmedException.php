<?php

namespace App\Exceptions;

use App\Enums\AuthMessages;
use App\Enums\EmailMessages;
use App\Enums\ErrorMessages;
use Exception;
use Illuminate\Http\JsonResponse;

class EmailAlreadyConfirmedException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => EmailMessages::EMAIL_ALREADY_CONFIRM->value
        ]);
    }
}
