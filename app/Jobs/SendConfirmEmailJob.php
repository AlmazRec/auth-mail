<?php

namespace App\Jobs;

use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class sendConfirmEmailJob implements ShouldQueue
{
    use Queueable;

    private string $email;
    private string $confirmationToken;
    private EmailService $emailService;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, string $confirmationToken, EmailService $emailService)
    {
        $this->email = $email;
        $this->confirmationToken = $confirmationToken;
        $this->emailService = $emailService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->emailService->sendConfirmEmail($this->email, $this->confirmationToken);
    }
}
