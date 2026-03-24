<?php

namespace App\Models\Order;

use App\Database;

class OrderRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function save(array $data): array
    {
        $items = json_encode($data['items']);
        $total = $data['totalPrice'];

        $stmt = $this->pdo->prepare("INSERT INTO orders (items, total_price) VALUES (?, ?)");
        $stmt->execute([$items, $total]);

        $id = $this->pdo->lastInsertId();

        return [
            'id' => $id,
            'items' => $items,
            'total_price' => $total,
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
}
