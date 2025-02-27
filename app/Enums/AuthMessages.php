<?php

namespace App\Enums;

enum AuthMessages: string
{
    case SUCCESS_JOIN = 'Вы успешно вошли.';
    case SUCCESS_REGISTER = 'Теперь вы можете авторизоваться на сайте.';
    case SUCCESS_EXIT = 'Вы успешно вышли.';
    case INCORRECT_CREDENTIALS = 'Неверный логин или пароль';
}
