<?php

namespace App\Jobs;

use App\Services\Interfaces\EmailServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWelcomeEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected EmailServiceInterface $emailService;

    private string $name;

    private string $email;

    public function __construct($name, $email, EmailServiceInterface $emailService)
    {
        $this->name = $name;
        $this->email = $email;
        $this->emailService = $emailService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->emailService->sendWelcomeEmail($this->email, $this->name);
    }
}
