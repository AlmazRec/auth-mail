<?php



\Illuminate\Support\Facades\Route::get('/test', function () {
    \Illuminate\Support\Facades\Mail::to('AlmazRec7820@yandex.ru')->send(new \App\Mail\WelcomeMail('Almaz'));
});
// +7 996 320‒16‒11 • AlmazRec7820@yandex.ru
