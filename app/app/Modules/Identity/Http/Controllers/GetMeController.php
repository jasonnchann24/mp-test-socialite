<?php

namespace App\Modules\Identity\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Ports\Auth\TokenDecoder;
use App\Ports\Identity\IdentityException;
use App\Ports\Identity\UserReads;
use Illuminate\Http\Request;

class GetMeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users/profile",
     *     summary="Get current user profile",
     *     description="Returns the authenticated user's profile with linked social providers.",
     *     tags={"Identity"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Current user",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(
     *                         property="providers",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="provider", type="string", example="google"),
     *                             @OA\Property(property="provider_user_id", type="string", example="1234567890"),
     *                             @OA\Property(property="email", type="string", example="john@example.com"),
     *                             @OA\Property(property="avatar", type="string", example="https://example.com/avatar.jpg")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid or missing token",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid or missing token.")
     *         )
     *     )
     * )
     */
    public function __construct(
        private readonly TokenDecoder $tokenDecoder,
        private readonly UserReads $userReads
    ) {}

    public function __invoke(Request $request)
    {
        $token = $request->bearerToken();

        $userId = $this->tokenDecoder->decodeUserId($token ?? '');

        $user = $this->userReads->findById($userId);

        if (! $user) {
            throw IdentityException::userNotFound($userId);
        }

        return $this->success(['user' => $user->toArray()]);
    }
}
