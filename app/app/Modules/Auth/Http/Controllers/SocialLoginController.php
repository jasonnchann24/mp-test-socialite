<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Modules\Auth\Actions\SocialLoginAction;
use App\Modules\Auth\Http\Requests\SocialLoginRequest;

class SocialLoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/{provider}",
     *     summary="Social login (Google/Facebook) and issue JWT tokens",
     *     description="https://developers.google.com/oauthplayground/ <br/> Google OAuth2 API v2 -> Authorize APIs -> Exchange authorization code for tokens <br/><br/> https://developers.facebook.com/docs/graph-api/overview",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="provider",
     *         in="path",
     *         required=true,
     *         description="Social provider",
     *         @OA\Schema(type="string", enum={"google","facebook"})
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"access_token"},
     *             @OA\Property(property="access_token", type="string", example="ya29.a0AfH6S...") 
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Logged in via social",
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
     *         response=400,
     *         description="Unsupported provider",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unsupported social provider.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid social token",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid social token.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Email required",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Email is required from social provider.")
     *         )
     *     )
     * )
     */
    public function __construct(private readonly SocialLoginAction $action) {}

    public function __invoke(SocialLoginRequest $request)
    {
        $validated = $request->validated();

        $provider = $validated['provider'] ?? $request->route('provider');

        $result = $this->action->run($provider, $validated['access_token']);

        return $this->success($result->toArray());
    }
}
