<?php

namespace App\Models\Category;

use App\Database;

class CategoryRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT id, name FROM categories");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return [['id' => 0, 'name' => 'DB Error: ' . $e->getMessage()]];
        }
    }
}
