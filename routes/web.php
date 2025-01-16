<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\FinetuneController;
use App\Http\Controllers\Api\ModelController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('v1')->group(function () {
    // Document endpoints
    Route::post('/documents', [DocumentController::class, 'upload']);
    Route::get('/documents', [DocumentController::class, 'index']);

    // Chat endpoints
    Route::post('/chat', [ChatController::class, 'chat']);

    // Model endpoints
    Route::get('/models', [ModelController::class, 'index']);
    Route::get('/models/{modelId}', [ModelController::class, 'show']);

    // Finetune endpoints
    Route::post('/finetune', [FinetuneController::class, 'start']);
    Route::get('/finetune/{jobId}', [FinetuneController::class, 'status']);
});
