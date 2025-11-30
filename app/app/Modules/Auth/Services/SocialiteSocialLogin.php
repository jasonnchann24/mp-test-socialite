<?php

namespace App\Modules\Auth\Services;

use App\Ports\Auth\SocialAuthException;
use App\Ports\Auth\SocialLogin;
use App\Ports\Auth\SocialUserData;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Throwable;

class SocialiteSocialLogin implements SocialLogin
{
    public function userFromToken(string $provider, string $accessToken): SocialUserData
    {
        if (! in_array($provider, ['google', 'facebook'], true)) {
            throw SocialAuthException::invalidProvider();
        }

        try {
            /** @var AbstractProvider $driver */
            $driver = Socialite::driver($provider);
            $socialUser = $driver->userFromToken($accessToken);
        } catch (Throwable) {
            throw SocialAuthException::invalidToken();
        }

        return new SocialUserData(
            provider: $provider,
            providerUserId: $socialUser->getId() ?? '',
            email: $socialUser->getEmail(),
            name: $socialUser->getName(),
            avatar: $socialUser->getAvatar(),
        );
    }
}
