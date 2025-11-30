<?php

namespace App\Core\Http\Middleware;

use App\Ports\Auth\TokenDecoder;
use Closure;
use Illuminate\Http\Request;

class DecodeUserFromToken
{
    public function __construct(private readonly TokenDecoder $decoder)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken() ?? '';

        $userId = $this->decoder->decodeUserId($token);

        $request->attributes->set('auth_user_id', $userId);

        return $next($request);
    }
}
