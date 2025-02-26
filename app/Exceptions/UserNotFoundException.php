<?php

namespace App\Exceptions;

use App\Enums\ErrorMessages;
use Exception;
use Illuminate\Http\JsonResponse;

class UserNotFoundException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => ErrorMessages::USER_NOT_FOUND->value,
        ]);
    }
}
