<?php

use App\Modules\Identity\Http\Controllers\GetMeController;
use App\Modules\Identity\Http\Controllers\UpdateMeController;
use Illuminate\Support\Facades\Route;

Route::prefix('identity')->group(function () {
    Route::middleware('decode.user')->group(function () {
        Route::get('me', GetMeController::class);
        Route::put('me', UpdateMeController::class);
    });
});

Route::prefix('users')->group(function () {
    Route::middleware('decode.user')->group(function () {
        Route::get('profile', GetMeController::class);
        Route::put('profile', UpdateMeController::class);
    });
});
