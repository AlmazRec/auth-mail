<?php

use App\Http\Controllers\Api\V1\EmailController;
use Illuminate\Support\Facades\Route;


Route::get('/v1/confirm-email/{confirmationToken}', [EmailController::class, 'confirmEmail'])->name('confirm-email');
Route::post('/v1/send-confirm-email', [EmailController::class, 'sendConfirmEmail'])->name('send-confirm-email');

// тут роуты для работы с письмами
