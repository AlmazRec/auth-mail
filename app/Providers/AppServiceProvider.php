<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\EmailConfirmationService;
use App\Services\EmailService;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\Interfaces\EmailConfirmationInterface;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\TokenServiceInterface;
use App\Services\TokenService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(EmailServiceInterface::class, EmailService::class);
        $this->app->bind(TokenServiceInterface::class, TokenService::class);
        $this->app->bind(EmailConfirmationInterface::class, EmailConfirmationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
