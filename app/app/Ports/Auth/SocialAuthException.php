<?php

namespace App\Ports\Auth;

use RuntimeException;

class SocialAuthException extends RuntimeException
{
    public function __construct(string $message, public readonly string $reason, public readonly ?int $httpCode = 400)
    {
        parent::__construct($message);
    }

    public static function invalidProvider(): self
    {
        return new self('Unsupported social provider.', 'invalid_provider', 422);
    }

    public static function invalidToken(): self
    {
        return new self('Invalid social token.', 'invalid_token', 400);
    }

    public static function emailRequired(): self
    {
        return new self('Email is required from social provider.', 'email_required', 422);
    }
}
