<?php

namespace App\Ports\Auth;

interface TokenDecoder
{
    /**
     * Decode a bearer token and return the subject identifier.
     *
     * @throws AuthException
     */
    public function decodeUserId(string $token): int|string;
}
