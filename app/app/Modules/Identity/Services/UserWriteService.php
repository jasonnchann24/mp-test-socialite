<?php

namespace App\Modules\Identity\Services;

use App\Ports\Identity\UserWrites;
use App\Modules\Identity\Models\User;
use App\Modules\Identity\Models\UserSocialProvider;
use App\Ports\Identity\IdentityException;
use App\Ports\Identity\UserData;
use Illuminate\Support\Facades\Hash;

class UserWriteService implements UserWrites
{
    public function create(string $name, string $email, string $password): UserData
    {
        $exists = User::where('email', $email)->exists();
        if ($exists) {
            throw IdentityException::emailExists($email);
        }
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return new UserData($user->id, $user->name, $user->email);
    }

    public function update(int|string $id, array $attributes): UserData
    {
        /** @var User|null $user */
        $user = User::find($id);

        if (! $user) {
            throw IdentityException::userNotFound($id);
        }

        if (isset($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        $user->fill($attributes);
        $user->save();

        return new UserData($user->id, $user->name, $user->email);
    }

    public function linkProvider(UserData $user, string $provider, string $providerUserId, ?string $email = null, ?string $avatar = null): void
    {
        UserSocialProvider::updateOrCreate(
            [
                'provider' => $provider,
                'provider_user_id' => $providerUserId,
            ],
            [
                'user_id' => $user->id,
                'email' => $email,
                'avatar' => $avatar,
            ]
        );
    }
}
