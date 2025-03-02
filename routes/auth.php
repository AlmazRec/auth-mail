<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ResetPasswordController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;



Route::prefix('/v1')->group(function () {
    Route::post('/sign-up', [AuthController::class, 'signUp'])->name('sign-up');
    Route::post('/sign-in', [AuthController::class, 'signIn'])->name('sign-in');
});


Route::prefix('/v1')->middleware(AuthMiddleware::class)->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::prefix('/v1/password')->group(function () {
    Route::post('/reset-request', [ResetPasswordController::class, 'passwordResetRequest'])->name('password.reset-request');
    Route::put('/reset/{resetToken}', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');
});
