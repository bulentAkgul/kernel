<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->group(function () {
    Route::middleware(['admin', 'api', 'auth:sanctum'])->group(function () {
        Route::apiResources([]);
    });
});