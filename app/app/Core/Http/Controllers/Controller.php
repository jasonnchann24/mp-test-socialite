<?php

namespace App\Core\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * Standard JSON success response.
     */
    protected function success(array $data = [], int $status = 200, array $headers = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $status, $headers);
    }

    /**
     * Standard JSON error response.
     */
    protected function error(string $message, int $status = 400, array $data = [], array $headers = []): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $payload['data'] = $data;
        }

        return response()->json($payload, $status, $headers);
    }
}
