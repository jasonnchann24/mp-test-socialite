<?php

namespace App\Ports\Auth;

use RuntimeException;

class AuthException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly string $reason,
        public readonly ?int $httpCode = 400

    ) {
        parent::__construct($message);
    }

    public static function invalidToken(): self
    {
        return new self('Invalid or missing token.', 'invalid_token', httpCode: 401);
    }
}
