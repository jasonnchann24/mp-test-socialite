<?php

namespace App\Ports\Auth;

interface SocialLogin
{
    /**
     * Resolve a social user using an access token from a provider.
     *
     * @throws AuthException
     */
    public function userFromToken(string $provider, string $accessToken): SocialUserData;
}
