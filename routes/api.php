<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\EmailConfirmMiddleware;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
require __DIR__ . '/email.php';


Route::prefix('v1/books')->middleware([AuthMiddleware::class, EmailConfirmMiddleware::class])->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('books.index');
    Route::get('/{book}', [BookController::class, 'show'])->name('books.show');
});

// тут роуты для получения книг из бд


