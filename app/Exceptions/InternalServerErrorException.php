<?php

namespace App\Exceptions;

use App\Enums\ErrorMessages;
use Exception;
use Illuminate\Http\JsonResponse;

class InternalServerErrorException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => ErrorMessages::INTERNAL_SERVER_ERROR->value
        ], 500);
    }
}
