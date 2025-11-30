<?php

$providers = [
    App\Core\Providers\AppServiceProvider::class,
    App\Modules\Auth\Providers\AuthServiceProvider::class,
    App\Modules\Identity\Providers\IdentityServiceProvider::class,
];

if (app()->environment('local')) {
    $providers[] = L5Swagger\L5SwaggerServiceProvider::class;
}

return $providers;
