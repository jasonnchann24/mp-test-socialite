<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Ports\Auth\AuthException;
use App\Ports\Auth\SocialAuthException;
use App\Ports\Identity\IdentityException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: "api"
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(append: 'throttle:api');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->httpCode);
        });
        $exceptions->render(function (SocialAuthException $e) {
            $status = match ($e->reason) {
                'invalid_provider' => 400,
                'invalid_token' => 401,
                'email_required' => 422,
                default => 400,
            };

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $status);
        });
        $exceptions->render(function (IdentityException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->httpCode);
        });
    })->create();
