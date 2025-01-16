<?php

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\FinetuneController;
use Illuminate\Support\Facades\Route;


Route::prefix('finetune')->group(function () {
    // Finetuning job management
    Route::post('/', [FinetuneController::class, 'start'])
        ->name('api.finetune.start');

    Route::get('/jobs', [FinetuneController::class, 'jobs'])
        ->name('api.finetune.jobs');

    Route::get('/jobs/{job}', [FinetuneController::class, 'status'])
        ->name('api.finetune.status');

    Route::post('/jobs/{job}/cancel', [FinetuneController::class, 'cancel'])
        ->name('api.finetune.cancel');

    // Training data management
    Route::post('/data', [FinetuneController::class, 'uploadData'])
        ->name('api.finetune.data.upload');

    Route::get('/data', [FinetuneController::class, 'listData'])
        ->name('api.finetune.data.list');

    Route::delete('/data/{dataset}', [FinetuneController::class, 'deleteData'])
        ->name('api.finetune.data.delete');
});
