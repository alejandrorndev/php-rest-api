<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Middleware\RoutingMiddleware;
use Tuupola\Middleware\CorsMiddleware;

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();


$app = AppFactory::create();

// Agregar middleware CORS
$app->add(new CorsMiddleware([
    "origin" => ["*"], // Permite cualquier origen. Puedes restringirlo a dominios específicos.
    "methods" => ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
    "headers.allow" => ["Content-Type", "Authorization"],
    "headers.expose" => [],
    "credentials" => false,
    "cache" => 0,
]));

// Middleware de enrutamiento
$app->addRoutingMiddleware();

// Middleware de manejo de errores
$errorMiddleware = $app->addErrorMiddleware(true, true, true);


require __DIR__ . '/../src/Routes/web.php';

$app->run();
