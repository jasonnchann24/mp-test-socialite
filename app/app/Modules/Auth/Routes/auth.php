<?php

use App\Modules\Auth\Http\Controllers\LoginController;
use App\Modules\Auth\Http\Controllers\RegisterController;
use App\Modules\Auth\Http\Controllers\TokenRefreshController;
use App\Modules\Auth\Http\Controllers\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);
    Route::post('refresh', TokenRefreshController::class);
    Route::post('google', SocialLoginController::class)->defaults('provider', 'google');
    Route::post('facebook', SocialLoginController::class)->defaults('provider', 'facebook');
});
