<?php
namespace App\Repositories;

use App\DAO\TaskDAO;
use App\Models\Task;

class TaskRepository {
    private $taskDAO;

    public function __construct() {
        $this->taskDAO = new TaskDAO();
    }

    public function AllTasks(): array {
        return $this->taskDAO->getAll();
    }

    public function createTask(Task $task): bool {
        return $this->taskDAO->create($task);
    }

    public function updateTask(int $id, array $data): bool {
        return $this->taskDAO->updateTask($id, $data);
    }

    // ... otros métodos
}