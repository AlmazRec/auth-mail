<?php

namespace App\Exceptions;

use App\Enums\ErrorMessages;
use Exception;
use Illuminate\Http\JsonResponse;

class ConfirmationTokenStoreException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => ErrorMessages::FAILED_TO_SAVE_CONFIRMATION_TOKEN->value,
        ]);
    }
}
