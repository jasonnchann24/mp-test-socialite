<?php

namespace App\Ports\Identity;

readonly class UserData
{
    public function __construct(
        public int|string $id,
        public string $name,
        public string $email,
        /** @var array<int, array<string, mixed>> */
        public array $providers = [],
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'providers' => $this->providers,
        ];
    }
}
