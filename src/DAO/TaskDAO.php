<?php
namespace App\DAO;

use PDO;
use App\Models\Task;
use App\Config\Database;

class TaskDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM tasks ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(Task $task): bool {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (title, description, status) 
            VALUES (:title, :description, :status)
        ");

        return $stmt->execute([
            ':title' => $task->getTitle(),
            ':description' => $task->getDescription(),
            ':status' => $task->getStatus()
        ]);
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("
            UPDATE tasks 
            SET status = :status 
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }

    //  (delete)
}