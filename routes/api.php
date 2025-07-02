<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Session\Middleware\StartSession;

Route::middleware(['api', StartSession::class])->group(function () {
    // создание email с лимитом
    Route::post('/emails', [EmailController::class, 'store'])
        ->middleware('throttle:10,60');

    // получение или создание email для сессии
    Route::get('/email', [EmailController::class, 'getOrCreateEmail']);

    // список писем и удаление ящика работают через email_id в сессии
    Route::get('/messages', [MessageController::class, 'index']);
    Route::delete('/email', [EmailController::class, 'destroy']);
}); 