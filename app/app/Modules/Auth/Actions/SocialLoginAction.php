<?php

namespace App\Modules\Auth\Actions;

use App\Modules\Auth\Services\TokenIssuer;
use App\Ports\Auth\SocialAuthException;
use App\Ports\Auth\SocialLogin;
use App\Ports\Auth\TokenData;
use App\Ports\Identity\UserReads;
use App\Ports\Identity\UserWrites;

class SocialLoginAction
{
    public function __construct(
        private readonly SocialLogin $socialLogin,
        private readonly UserReads $userReads,
        private readonly UserWrites $userWrites,
        private readonly TokenIssuer $tokenIssuer,
    ) {}

    public function run(string $provider, string $accessToken): TokenData
    {
        $socialUser = $this->socialLogin->userFromToken($provider, $accessToken);

        $user = $this->userReads->findByProvider($socialUser->provider, $socialUser->providerUserId);

        if (! $user && $socialUser->email) {
            $user = $this->userReads->findByEmail($socialUser->email);
        }

        if (! $user) {
            if (! $socialUser->email) {
                throw SocialAuthException::emailRequired();
            }

            $user = $this->userWrites->create(
                $socialUser->name ?? 'Social User',
                $socialUser->email,
                bin2hex(random_bytes(16))
            );
        }

        $this->userWrites->linkProvider(
            $user,
            $socialUser->provider,
            $socialUser->providerUserId,
            $socialUser->email,
            $socialUser->avatar
        );

        return $this->tokenIssuer->issue($user);
    }
}
