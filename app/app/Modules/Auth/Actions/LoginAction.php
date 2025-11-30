<?php

namespace App\Modules\Auth\Actions;

use App\Ports\Identity\UserReads;
use App\Modules\Auth\Services\TokenIssuer;
use App\Ports\Auth\TokenData;

class LoginAction
{
    public function __construct(
        private readonly UserReads $userReads,
        private readonly TokenIssuer $tokenIssuer,
    ) {}

    public function run(string $email, string $password): TokenData
    {
        $user = $this->userReads->authenticate($email, $password);

        
        return $this->tokenIssuer->issue($user);
    }
}
