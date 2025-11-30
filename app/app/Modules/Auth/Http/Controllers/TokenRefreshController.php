<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Modules\Auth\Actions\LoginAction;
use App\Modules\Auth\Actions\TokenRefreshAction;
use Illuminate\Http\Request;

class TokenRefreshController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/refresh",
     *     summary="Refresh JWT token",
     *     description="Currently Refresh token will be sent in body, future enhancement Refresh token will be sent in http cookies for web clients",
     *     tags={"Auth"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john@example.com")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="eyJhbGciOi..."),
     *                 @OA\Property(property="refresh_token", type="string", example="eyJhbGciOi..."),
     *                 @OA\Property(property="token_type", type="string", example="bearer"),
     *                 @OA\Property(property="expires_in", type="integer", example=3600),
     *                 @OA\Property(property="refresh_expires_in", type="integer", example=1209600)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid or missing refresh token",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid or missing token.")
     *         )
     *     )
     * )
     */
    public function __construct(private readonly TokenRefreshAction $tokenRefreshAction) {}

    public function __invoke(Request $request)
    {
        $refreshToken = $request->bearerToken() ?? '';

        $result = $this->tokenRefreshAction->run($refreshToken);

        return $this->success($result->toArray());
    }
}
