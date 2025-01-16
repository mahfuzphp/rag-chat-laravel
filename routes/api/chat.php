<?php

use App\Http\Controllers\Api\ChatController;
use Illuminate\Support\Facades\Route;

Route::prefix('chat')->group(function () {
    // Chat endpoints
    Route::post('/', [ChatController::class, 'send'])
        ->name('api.chat.send');
    
    Route::get('/history', [ChatController::class, 'history'])
        ->name('api.chat.history');
    
    Route::delete('/history', [ChatController::class, 'clearHistory'])
        ->name('api.chat.clear');
});