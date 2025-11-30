<?php

namespace App\Modules\Identity\Providers;

use App\Modules\Identity\Services\UserWriteService;
use App\Modules\Identity\Services\UserReadService;
use App\Ports\Identity\UserReads;
use App\Ports\Identity\UserWrites;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(UserWrites::class, UserWriteService::class);
        $this->app->singleton(UserReads::class, UserReadService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        Route::prefix('api/v1')
            ->middleware('api')
            ->group(__DIR__ . '/../Routes/identity.php');
    }
}
