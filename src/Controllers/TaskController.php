<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\TaskRepository;
use App\Models\Task;

class TaskController {
    private $taskRepository;

    public function __construct() {
        $this->taskRepository = new TaskRepository();
    }

    public function getAll(Request $request, Response $response): Response {
        $tasks = $this->taskRepository->getAllTasks();
        $response->getBody()->write(json_encode($tasks));
        return $response->withHeader('Content-Type', 'application/json');
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
    

    public function updateStatus(Request $request, Response $response, array $args): Response {
        $id = (int)$args['id'];
        $data = $request->getParsedBody();
        $status = $data['status'];

        $result = $this->taskRepository->updateTaskStatus($id, $status);
        
        $response->getBody()->write(json_encode(['success' => $result]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}