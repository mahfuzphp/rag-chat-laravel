<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['api', 'auth:sanctum'])->group(function () {
    require __DIR__ . '/api/chat.php';
    require __DIR__ . '/api/documents.php';
    require __DIR__ . '/api/models.php';
    require __DIR__ . '/api/finetune.php';
});
