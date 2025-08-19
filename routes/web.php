<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramBotController;

// Telegram Bot Webhook - asosiy endpoint
Route::post('/api/telegram/webhook', [TelegramBotController::class, 'webhook']);
