<?php

namespace App\Modules\Auth\Providers;

use App\Modules\Auth\Http\Middleware\DecodeUserFromToken;
use App\Modules\Auth\Services\JwtTokenDecoder;
use App\Modules\Auth\Services\SocialiteSocialLogin;
use App\Ports\Auth\SocialLogin;
use App\Ports\Auth\TokenDecoder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(__DIR__ . '/../Routes/auth.php');
    }

    public function register(): void
    {
        $this->app->singleton(TokenDecoder::class, JwtTokenDecoder::class);
        $this->app->singleton(SocialLogin::class, SocialiteSocialLogin::class);
    }
}
