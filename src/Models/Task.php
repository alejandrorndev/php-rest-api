<?php
namespace App\Models;

class Task {
    private $id;
    private $title;
    private $description;
    private $status;

    // Getters y Setters
    public function getId(): int {
        return $this->id;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setStatus(string $status): void {
        if (!in_array($status, ['pendiente', 'completada'])) {
            throw new \InvalidArgumentException("Estado no válido");
        }
        $this->status = $status;
    }

    public function getStatus(): string {
        return $this->status;
    }
}