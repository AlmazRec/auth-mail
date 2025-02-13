<?php

namespace App\Enums;

enum ErrorMessages: string
{
    case INVALID_TOKEN = 'Неверный токен.';
    case INTERNAL_SERVER_ERROR = 'Ошибка сервера. Попробуйте позже.';
}
