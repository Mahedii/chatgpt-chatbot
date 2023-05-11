<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatGptController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('bot');
});

Route::get('/chatbot', function () {
    return view('chatbot');
});

Route::post('/bot/send', [ChatGptController::class, 'handleMessage'])->name('bot.send');

Route::post('/chatbot/send', [ChatGptController::class, 'sendMessage'])->name('chatbot.send');
