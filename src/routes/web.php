<?php
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\TaskController;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/openapi.yaml', function (Request $request, Response $response) {
    $file = __DIR__ . '/../public/openapi.yaml';
    $response->getBody()->write(file_get_contents($file));
    return $response->withHeader('Content-Type', 'text/yaml');
});

$app->group('/api/tasks', function (RouteCollectorProxy $group) {
    $taskController = TaskController::class;

    $group->get('/list', [$taskController, 'getAll']);
    $group->post('/create', [$taskController, 'create']);
    $group->put('/{id}', [$taskController, 'updated']);
    $group->delete('/delete/{id}', [$taskController, 'delete']);
});