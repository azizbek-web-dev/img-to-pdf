<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramBotController;

// Asosiy sahifa
Route::get('/', function () {
    return view('home');
});

// Telegram Bot Webhook - asosiy endpoint
Route::post('/api/telegram/webhook', [TelegramBotController::class, 'webhook']);
