<?php

namespace App\Modules\Auth\Services;

use App\Ports\Auth\TokenData;
use App\Ports\Identity\UserData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\JWTGuard;

class TokenIssuer
{
    public function issue(UserData $user): TokenData
    {
        $guard = Auth::guard('api');

        if (! $guard instanceof JWTGuard) {
            throw new \RuntimeException('Authentication guard misconfigured.');
        }

        $accessToken = $guard->claims(['typ' => 'access'])->tokenById($user->id);

        if (! $accessToken) {
            throw new \RuntimeException('Unable to generate token.');
        }

        $factory = $guard->factory();
        $originalTtl = $factory->getTTL();

        $factory->setTTL(Config::get('jwt.refresh_ttl'));
        $refreshToken = $guard->claims(['typ' => 'refresh'])->tokenById($user->id);
        $factory->setTTL($originalTtl);

        return new TokenData(
            user: $user,
            token: $accessToken,
            refreshToken: $refreshToken,
            tokenType: 'bearer',
            expiresIn: Config::get('jwt.ttl') * 60,
            refreshExpiresIn: Config::get('jwt.refresh_ttl') * 60,
        );
    }
}
