<?php

namespace App\Ports\Identity;

use RuntimeException;

class IdentityException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly string $reason,
        public readonly ?string $email = null,
        public readonly ?int $httpCode = 400
    ) {
        parent::__construct($message);
    }

    public static function emailExists(string $email): self
    {
        return new self(
            "User with email {$email} already exists.",
            reason: 'email_exists',
            email: $email,
            httpCode: 409
        );
    }

    public static function invalidCredentials(): self
    {
        return new self(
            'Invalid credentials.',
            reason: 'invalid_credentials',
            httpCode: 422
        );
    }

    public static function userNotFound(int|string $id): self
    {
        return new self(
            "User {$id} not found.",
            reason: 'user_not_found',
            httpCode: 404
        );
    }
}
