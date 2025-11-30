<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Modules\Auth\Http\Requests\LoginFormRequest;
use App\Modules\Auth\Actions\LoginAction;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Login and issue JWT tokens",
     *     description="Currently Refresh token will be sent in body, future enhancement Refresh token will be sent in http cookies for web clients",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Logged in",
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
     *         response=422,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid credentials.")
     *         )
     *     )
     * )
     */
    public function __construct(private readonly LoginAction $loginAction) {}

    public function __invoke(LoginFormRequest $request)
    {
        $validated = $request->validated();

        $result = $this->loginAction->run(
            $validated['email'],
            $validated['password']
        );

        // TODO: For further implementation, refresh token should be sent in http cookie if Web.
        // Currently just for demo purposes I sent refresh token in the response body.

        return $this->success($result->toArray());
    }
}
