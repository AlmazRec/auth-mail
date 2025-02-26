<?php

namespace App\Enums;

enum ErrorMessages: string
{
    case FAILED_TO_SAVE_CONFIRMATION_TOKEN = 'Не удалось сохранить токен. Попробуйте позже.';
    case INVALID_TOKEN = 'Неверный токен.';

    case USER_NOT_FOUND = 'Пользователь не найден. Попробуйте позже.';
    case INTERNAL_SERVER_ERROR = 'Ошибка сервера. Попробуйте позже.';
}
