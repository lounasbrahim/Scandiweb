<?php

namespace App\Models\Order;

class Order
{
    private ?int $id;
    private array $items;
    private float $totalPrice;
    private ?string $createdAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->items = is_string($data['items']) ? json_decode($data['items'], true) : ($data['items'] ?? []);
        $this->totalPrice = (float)($data['total_price'] ?? $data['totalPrice'] ?? 0);
        $this->createdAt = $data['created_at'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'items' => json_encode($this->items),
            'total_price' => $this->totalPrice,
            'created_at' => $this->createdAt,
        ];
    }
}
