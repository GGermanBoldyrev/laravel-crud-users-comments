<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Laravel CRUD Users & Comments API",
 *     description="Документация к API аутентификации, постов и комментариев"
 * )
 *
 * @OA\Server(
 *     url="http://localhost/api",
 *     description="Local API server"
 * )
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Введите Bearer токен"
 * )
 */
class OpenApi
{

}
