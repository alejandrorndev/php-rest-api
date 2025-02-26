<?php
namespace App\Controllers;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskController {
    private $taskRepository;

    public function __construct() {
        $this->taskRepository = new TaskRepository();
    }

    public function create(Request $request, Response $response): Response {
        $data = json_decode($request->getBody()->getContents(), true) ?? [];
        
        // Validación de campos obligatorios
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
    
    public function getAll(Request $request, Response $response): Response {
        $tasks = $this->taskRepository->AllTasks();
        $response->getBody()->write(json_encode($tasks));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function updateTask(Request $request, Response $response, array $args): Response {
        $id = (int)$args['id'];
        $data = json_decode($request->getBody()->getContents(), true) ?? [];
       
        if (empty($data['title'])) {
            $response->getBody()->write(json_encode(['error' => 'El título no puede ser vacio']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        if (empty(trim($data['description']))) {
            $response->getBody()->write(json_encode(['error' => 'La descripción o puede ser vacia']));
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
                'message' => 'Tarea actualizado exitosamente',
                'taskID' => $id
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Error al actulizar la tarea'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}