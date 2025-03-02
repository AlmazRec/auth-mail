<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\EmailConfirmationServiceService;
use App\Services\EmailService;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\Interfaces\EmailConfirmationServiceInterface;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\ResetPasswordServiceInterface;
use App\Services\Interfaces\TokenServiceInterface;
use App\Services\ResetPasswordService;
use App\Services\TokenService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);

        $this->app->bind(ResetPasswordServiceInterface::class, ResetPasswordService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(EmailServiceInterface::class, EmailService::class);
        $this->app->bind(TokenServiceInterface::class, TokenService::class);
        $this->app->bind(EmailConfirmationServiceInterface::class, EmailConfirmationServiceService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
