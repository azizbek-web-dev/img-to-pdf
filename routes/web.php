<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramBotController;

// Asosiy sahifa
Route::get('/', function () {
    return view('home');
});

// Telegram Bot Webhook - CSRF dan himoyalangan
Route::post('/api/telegram/webhook', [TelegramBotController::class, 'webhook'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
