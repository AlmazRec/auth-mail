<?php

namespace App\Services;

use App\Mail\ConfirmMail;
use App\Mail\WelcomeMail;
use App\Services\Interfaces\EmailServiceInterface;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Mail;

class EmailService implements EmailServiceInterface
{

    public function sendWelcomeEmail(string $email, string $name): void
    {
        Mail::to($email)->send(new WelcomeMail($name));
    }

    public function sendConfirmEmail(string $email, string $confirmationToken): ?SentMessage
    {
        return Mail::to($email)->send(new ConfirmMail($confirmationToken));
    }
}
