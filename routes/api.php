<?php

require __DIR__ . '/auth.php';
require __DIR__ . '/email.php';


\Illuminate\Support\Facades\Route::get('/send', function () {
    $service = new \App\Services\EmailService();
   \App\Jobs\SendWelcomeEmail::dispatch('almaz', 'almazrec7820@yandex.ru', $service);
});
