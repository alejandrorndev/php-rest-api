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

    public function updateTask(int $id, array $data): bool {
        $fields = [];
        $params = [':id' => $id];
    
        if (isset($data['title'])) {
            $fields[] = "title = :title";
            $params[':title'] = $data['title'];
        }
    
        if (isset($data['description'])) {
            $fields[] = "description = :description";
            $params[':description'] = $data['description'];
        }
    
        if (isset($data['status'])) {
            $fields[] = "status = :status";
            $params[':status'] = $data['status'];
        }
    
        if (empty($fields)) {
            return false;
        }
    
        $query = "UPDATE tasks SET " . implode(', ', $fields) . " WHERE id = :id";
    
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute($params);
    }
    

    //  (delete)
}