<?php
require_once __DIR__ . '/../Models/Repositories/TaskRepository.php';

class TaskService {
    private $taskRepository;

    public function __construct() {
        $this->taskRepository = new TaskRepository();
    }

    public function createTask(array $data) {
        $task = $this->taskRepository->createTask($data);
        // Ejemplo: Enviar notificación (lógica adicional)
        // $this->notifyAdmins($task);
        return $task;
    }
}