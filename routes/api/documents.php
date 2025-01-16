<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DocumentController;


Route::prefix('documents')->group(function () {
    Route::post('/', [DocumentController::class, 'upload'])
        ->name('api.documents.upload');
    
    Route::get('/', [DocumentController::class, 'index'])
        ->name('api.documents.index');
    
    Route::get('/{document}', [DocumentController::class, 'show'])
        ->name('api.documents.show');
    
    Route::delete('/{document}', [DocumentController::class, 'delete'])
        ->name('api.documents.delete');
    
    // Document processing endpoints
    Route::post('/{document}/process', [DocumentController::class, 'process'])
        ->name('api.documents.process');
    
    Route::get('/{document}/status', [DocumentController::class, 'status'])
        ->name('api.documents.status');
});