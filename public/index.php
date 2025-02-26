<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Middleware\RoutingMiddleware;

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad(); // Usar safeLoad() para evitar errores si falta el archivo .env

// Crear la aplicación Slim
$app = AppFactory::create();

// Middleware de enrutamiento (importante para que Slim maneje rutas correctamente)
$app->addRoutingMiddleware();

// Middleware de manejo de errores (evita errores 500 y muestra mensajes claros)
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Cargar rutas
require __DIR__ . '/../src/Routes/web.php';

// Ejecutar la aplicación
$app->run();
