<?php

namespace App\Enums;

enum ErrorMessages: string
{
    case INCORRECT_LOGIN_OR_PASSWORD = 'Неверный логин или пароль';
    case INTERNAL_SERVER_ERROR = 'Ошибка сервера. Попробуйте позже.';
    case BLANK_FIELD  = 'Заполните все поля.';
}
