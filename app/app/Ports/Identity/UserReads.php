<?php

namespace App\Ports\Identity;

interface UserReads
{
    public function authenticate(string $email, string $password): UserData;

    public function findByEmail(string $email): ?UserData;

    public function findById(int|string $id): ?UserData;

    public function findByProvider(string $provider, string $providerUserId): ?UserData;
}
