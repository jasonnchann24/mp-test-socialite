<?php

namespace App\Ports\Identity;

interface UserWrites
{
    /**
     * Persist a new user record.
     */
    public function create(string $name, string $email, string $password): UserData;

    /**
     * Update an existing user record.
     */
    public function update(int|string $id, array $attributes): UserData;

    /**
     * Link a social provider to an existing user.
     */
    public function linkProvider(UserData $user, string $provider, string $providerUserId, ?string $email = null, ?string $avatar = null): void;
}
