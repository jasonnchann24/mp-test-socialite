<?php

namespace App\Modules\Auth\Actions;

use App\Ports\Identity\UserReads;
use App\Ports\Identity\UserData;
use App\Ports\Identity\IdentityException;
use App\Ports\Auth\AuthException;
use App\Modules\Auth\Services\TokenIssuer;
use App\Ports\Auth\TokenData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTGuard;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenRefreshAction
{
    public function __construct(
        private readonly UserReads $userReads,
        private readonly TokenIssuer $tokenIssuer,
    ) {}

    public function run(string $refreshToken): TokenData
    {
        try {
            $payload = JWTAuth::setToken($refreshToken)->getPayload();
        } catch (JWTException) {
            throw AuthException::invalidToken();
        }

        if ($payload->get('typ') !== 'refresh') {
            throw AuthException::invalidToken();
        }

        $userId = $payload->get('sub');

        if ($userId === null) {
            throw AuthException::invalidToken();
        }

        $user = $this->userReads->findById($userId);

        if (! $user) {
            throw IdentityException::userNotFound($userId);
        }

        try {
            JWTAuth::setToken($refreshToken)->invalidate();
        } catch (JWTException) {
            throw AuthException::invalidToken();
        }

        return $this->tokenIssuer->issue($user);
    }
}
