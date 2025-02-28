<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Task;
use App\Repositories\TaskRepository;
use OpenApi\Annotations as OA;

class TaskController {
    private $taskRepository;

    public function __construct() {
        $this->taskRepository = new TaskRepository();
    }

    /**
     * @OA\Post(
     *     path="/api/tasks/create",
     *     tags={"Tareas"},
     *     summary="Crea una nueva tarea",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "status"},
     *             @OA\Property(property="title", type="string", example="Aprender Slim 4"),
     *             @OA\Property(property="description", type="string", example="Detalles de la tarea"),
     *             @OA\Property(property="status", type="string", enum={"pendiente", "completada"}, example="pendiente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarea creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tarea creada exitosamente"),
     *             @OA\Property(property="task", type="object",
     *                 @OA\Property(property="title", type="string", example="Aprender Slim 4"),
     *                 @OA\Property(property="description", type="string", example="Detalles de la tarea"),
     *                 @OA\Property(property="status", type="string", example="pendiente")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan campos obligatorios o error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El título es obligatorio")
     *         )
     *     )
     * )
     */
    public function create(Request $request, Response $response): Response {
        $data = json_decode($request->getBody()->getContents(), true) ?? [];
        
        if (!isset($data['title']) || empty($data['title'])) {
            $response->getBody()->write(json_encode(['error' => 'El título es obligatorio']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        if (!isset($data['description']) || empty(trim($data['description']))) {
            $response->getBody()->write(json_encode(['error' => 'La descripción es obligatoria']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        if (!isset($data['status']) || empty(trim($data['status']))) {
            $response->getBody()->write(json_encode(['error' => 'El estado es obligatorio']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        if (!in_array($data['status'], ['pendiente', 'completada'])) {
            $response->getBody()->write(json_encode(['error' => 'El estado debe ser pendiente o completada']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        $task = new Task();
        $task->setTitle($data['title']);
        $task->setDescription($data['description']);
        $task->setStatus($data['status']);
    
        $result = $this->taskRepository->createTask($task);
    
        if ($result) {
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Tarea creada exitosamente',
                'task' => [
                    'title' => $task->getTitle(),
                    'description' => $task->getDescription(),
                    'status' => $task->getStatus()
                ]
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Error al guardar la tarea'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/list",
     *     summary="Obtiene todas las tareas registradas",
     *     tags={"Tareas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tareas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=8),
     *                 @OA\Property(property="title", type="string", example="otro nuevo titulo"),
     *                 @OA\Property(property="description", type="string", example="Implementando Implementando Implementando Implementando"),
     *                 @OA\Property(property="status", type="string", example="pendiente")
     *             )
     *         )
     *     )
     * )
     */

    public function getAll(Request $request, Response $response): Response {
        $tasks = $this->taskRepository->AllTasks();
        $response->getBody()->write(json_encode($tasks));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Actualiza una tarea existente",
     *     tags={"Tareas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea a actualizar",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la tarea a actualizar",
     *         @OA\JsonContent(
     *             required={"title", "description", "status"},
     *             @OA\Property(property="title", type="string", example="dddddddddddddd dossss nuevo titulo"),
     *             @OA\Property(property="description", type="string", example="Implemewwwwwwwwntando Implementando Implementando Implementando"),
     *             @OA\Property(property="status", type="string", enum={"pendiente", "completada"}, example="pendiente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tarea actualizada exitosamente"),
     *             @OA\Property(property="taskID", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación: algún campo obligatorio está vacío",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El título no puede ser vacio")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar la tarea",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Error al actualizar la tarea")
     *         )
     *     )
     * )
     */

    public function updated(Request $request, Response $response, array $args): Response {
        $id = (int)$args['id'];
        $data = json_decode($request->getBody()->getContents(), true) ?? [];
       
        if (empty($data['title'])) {
            $response->getBody()->write(json_encode(['error' => 'El título no puede ser vacio']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        if (empty(trim($data['description']))) {
            $response->getBody()->write(json_encode(['error' => 'La descripción no puede ser vacia']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        if (empty(trim($data['status']))) {
            $response->getBody()->write(json_encode(['error' => 'El estado no puede ser vacio']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        if (!in_array($data['status'], ['pendiente', 'completada'])) {
            $response->getBody()->write(json_encode(['error' => 'El estado debe ser pendiente o completada']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        $result = $this->taskRepository->updateTask($id, $data);
        
        if ($result) {
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Tarea actualizada exitosamente',
                'taskID' => $id
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Error al actualizar la tarea'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/delete/{id}",
     *     summary="Elimina una tarea existente",
     *     tags={"Tareas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tarea eliminada exitosamente"),
     *             @OA\Property(property="taskID", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Falta el argumento 'id' en la solicitud",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Falta el argumento id en la solicitud")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la tarea",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Error al eliminar la tarea")
     *         )
     *     )
     * )
     */
    public function delete(Request $request, Response $response, array $args): Response {
        if (!isset($args['id']) || empty($args['id'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Falta el argumento "id" en la solicitud'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); // 400 Bad Request
        }

        $id = (int)$args['id'];

        $result = $this->taskRepository->deletedTask($id);

        if ($result) {
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Tarea eliminada exitosamente',
                'taskID' => $id
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Error al eliminar la tarea'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
