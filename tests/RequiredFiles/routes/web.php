<?php

use Illuminate\Support\Facades\Route;

Route::prefix('')->group(function () {
    Route::middleware(['web'])->group(function () {
        Route::resources([]);
    });
});