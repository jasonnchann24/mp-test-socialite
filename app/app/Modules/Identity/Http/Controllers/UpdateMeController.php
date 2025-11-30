<?php

namespace App\Modules\Identity\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Modules\Identity\Http\Requests\UserFormRequest;
use App\Ports\Identity\UserWrites;
use Illuminate\Http\Request;

class UpdateMeController extends Controller
{
    /**
     * @OA\Put(
     *     path="/users/profile",
     *     summary="Update current user profile",
     *     description="Update the authenticated user's profile. Password update is optional and must be confirmed.",
     *     tags={"Identity"},
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Updated"),
     *             @OA\Property(property="password", type="string", format="password", example="newsecret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newsecret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Updated"),
     *                     @OA\Property(property="email", type="string", example="john@example.com")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The password confirmation does not match.")
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
    public function __construct(private readonly UserWrites $userWrites) {}

    public function __invoke(UserFormRequest $request)
    {
        $userId = $request->attributes->get('auth_user_id');

        $data = $request->validated();

        $user = $this->userWrites->update($userId, $data);

        return $this->success(['user' => $user->toArray()]);
    }
}
