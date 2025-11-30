<?php

namespace App\Modules\Identity\Services;

use App\Modules\Identity\Models\User;
use App\Modules\Identity\Models\UserSocialProvider;
use App\Ports\Identity\UserReads;
use App\Ports\Identity\UserData;
use App\Ports\Identity\IdentityException;
use Illuminate\Support\Facades\Hash;

class UserReadService implements UserReads
{
    public function authenticate(string $email, string $password): UserData
    {
        $record = User::where('email', $email)->first();

        if (! $record || ! Hash::check($password, $record->password)) {
            throw IdentityException::invalidCredentials();
        }

        return new UserData($record->id, $record->name, $record->email);
    }

    public function findByEmail(string $email): ?UserData
    {
        /** @var User|null $user */
        $user = User::where('email', $email)->first();

        if (!$user) {
            return null;
        }

        return $this->mapUser($user);
    }

    public function findById(int|string $id): ?UserData
    {
        /** @var User|null $user */
        $user = User::find($id);

        if (! $user) {
            return null;
        }

        return $this->mapUser($user);
    }

    public function findByProvider(string $provider, string $providerUserId): ?UserData
    {
        /** @var UserSocialProvider|null $link */
        $link = UserSocialProvider::where('provider', $provider)
            ->where('provider_user_id', $providerUserId)
            ->first();

        if (! $link) {
            return null;
        }

        /** @var User|null $user */
        $user = User::find($link->user_id);

        if (! $user) {
            return null;
        }

        return $this->mapUser($user);
    }

    private function mapUser(User $user): UserData
    {
        $providers = UserSocialProvider::where('user_id', $user->id)->get()->map(function (UserSocialProvider $link) {
            return [
                'provider' => $link->provider,
                'provider_user_id' => $link->provider_user_id,
                'email' => $link->email,
                'avatar' => $link->avatar,
            ];
        })->values()->all();

        return new UserData(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            providers: $providers,
        );
    }
}
