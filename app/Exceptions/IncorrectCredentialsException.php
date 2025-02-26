<?php

namespace App\Exceptions;

use App\Enums\AuthMessages;
use Exception;
use Illuminate\Http\JsonResponse;

class IncorrectCredentialsException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => AuthMessages::INCORRECT_CREDENTIALS->value
        ], 401);
    }
}
