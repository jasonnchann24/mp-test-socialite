<?php

namespace App\Modules\Auth\Actions;

use App\Ports\Identity\UserWrites;
use App\Ports\Identity\UserData;
use Illuminate\Validation\ValidationException;

class RegisterAction
{
    public function __construct(
        private readonly UserWrites $userWrites
    ) {}

    public function run(string $name, string $email, string $password): UserData
    {
        return $this->userWrites->create($name, $email, $password);
    }
}
