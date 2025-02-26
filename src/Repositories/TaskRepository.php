<?php
namespace App\Repositories;

use App\DAO\TaskDAO;
use App\Models\Task;

class TaskRepository {
    private $taskDAO;

    public function __construct() {
        $this->taskDAO = new TaskDAO();
    }

    public function getAllTasks(): array {
        return $this->taskDAO->getAll();
    }

    public function createTask(Task $task): bool {
        return $this->taskDAO->create($task);
    }

    public function updateTaskStatus(int $id, string $status): bool {
        return $this->taskDAO->updateStatus($id, $status);
    }

    // ... otros métodos
}