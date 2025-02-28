<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Task CRUD API",
 *     version="1.0.0",
 *     description="API para gestionar tareas con Slim 4",
 *     @OA\Contact(
 *         email="soporte@miapi.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor local"
 * )
 *
 * @OA\PathItem(path="/")
 */
class SwaggerInfo {}
