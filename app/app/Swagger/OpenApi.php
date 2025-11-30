<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0"
 * )
 *
 * @OA\Server(
 *     url="/api/v1",
 *     description="API server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\PathItem(
 *     path="/"
 * )
 */
class OpenApi
{
    // This class holds top-level OpenAPI annotations for L5-Swagger.
}
