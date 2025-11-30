<?php

namespace App\Modules\Auth\Services;

use App\Ports\Auth\AuthException;
use App\Ports\Auth\TokenDecoder;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtTokenDecoder implements TokenDecoder
{
    public function decodeUserId(string $token): int|string
    {
        if (empty($token)) {
            throw AuthException::invalidToken();
        }

        try {
            $payload = JWTAuth::setToken($token)->getPayload();
        } catch (JWTException) {
            throw AuthException::invalidToken();
        }

        $sub = $payload->get('sub');

        if ($sub === null) {
            throw AuthException::invalidToken();
        }

        return $sub;
    }
}
