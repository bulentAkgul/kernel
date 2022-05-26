<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResources([]);
    });
});