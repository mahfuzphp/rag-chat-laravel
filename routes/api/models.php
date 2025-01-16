<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ModelController;

Route::prefix('models')->group(function () {
    // Model management endpoints
    Route::get('/', [ModelController::class, 'index'])
        ->name('api.models.index');
    
    Route::get('/{model}', [ModelController::class, 'show'])
        ->name('api.models.show');
    
    Route::post('/{model}/load', [ModelController::class, 'load'])
        ->name('api.models.load');
    
    Route::post('/{model}/unload', [ModelController::class, 'unload'])
        ->name('api.models.unload');
});