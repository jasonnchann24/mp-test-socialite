<?php

namespace App\Ports\Auth;

use App\Ports\Identity\UserData;

readonly class TokenData
{
    public function __construct(
        public UserData $user,
        public string $token,
        public string $refreshToken,
        public string $tokenType,
        public int $expiresIn,
        public int $refreshExpiresIn,
    ) {}

    public function toArray(): array
    {
        return [
            'user'               => $this->user->toArray(),
            'token'              => $this->token,
            'refresh_token'      => $this->refreshToken,
            'token_type'         => $this->tokenType,
            'expires_in'         => $this->expiresIn,
            'refresh_expires_in' => $this->refreshExpiresIn,
        ];
    }
}
