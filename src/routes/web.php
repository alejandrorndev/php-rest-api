<?php
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\TaskController;

$app->group('/api/tasks', function (RouteCollectorProxy $group) {
    $taskController = TaskController::class;

    $group->get('/list', [$taskController, 'getAll']);
    $group->post('/create', [$taskController, 'create']);
    $group->put('/{id}', [$taskController, 'updated']);
    $group->delete('/delete/{id}', [$taskController, 'delete']);
});