<?php

namespace App\Enums;

enum EmailMessages: string
{
    case SUCCESS_CONFIRM = 'Почта успешно подтверждена.';
    case EMAIL_ALREADY_CONFIRM = 'Почта уже подтверждена';
    case SUCCESS_SEND = 'Письмо успешно отправлено отправлено.';
}
