<?php

namespace App\Models\Product;

use App\Database;

abstract class AbstractProduct
{
    protected $pdo;
    protected $id;
    protected $name;
    protected $brand;
    protected $description;
    protected $inStock;
    protected $category;
    protected $price;
    protected $gallery = [];

    public function __construct(array $data)
    {
        $this->pdo = Database::connect();
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->brand = $data['brand'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->inStock = $data['in_stock'] ?? false;
        $this->category = $data['category'] ?? null;
    }

    abstract protected function getAdditionalFields(): array;

    public function loadRelations(): void
    {
        $this->loadPrice();
        $this->loadGallery();
    }

    private function loadPrice(): void
    {
        $stmt = $this->pdo->prepare("SELECT amount FROM prices WHERE product_id = ? LIMIT 1");
        $stmt->execute([$this->id]);
        $priceRow = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->price = $priceRow ? floatval($priceRow['amount']) : null;
    }

    private function loadGallery(): void
    {
        $stmt = $this->pdo->prepare("SELECT image_url FROM product_gallery WHERE product_id = ?");
        $stmt->execute([$this->id]);
        $this->gallery = array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'image_url');
    }

    public function toArray(): array
    {
        return array_merge([
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand,
            'description' => $this->description,
            'in_stock' => $this->inStock,
            'category' => $this->category,
            'price' => $this->price,
            'gallery' => $this->gallery,
        ], $this->getAdditionalFields());
    }
}
