<?php
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\TaskController;

$app->group('/api/tasks', function (RouteCollectorProxy $group) {
    $taskController = TaskController::class;

    $group->get('/list', [$taskController, 'getAll']);
    $group->post('/create', [$taskController, 'create']);
    $group->put('/{id}/status', [$taskController, 'updateStatus']);
    // ...(DELETE)
});