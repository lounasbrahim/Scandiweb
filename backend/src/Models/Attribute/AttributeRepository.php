<?php

namespace App\Models\Attribute;

use App\Database;

class AttributeRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function findByProductId(string $productId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT a.id, a.attribute_id, a.name, a.type,
                    ai.item_id, ai.display_value, ai.value
             FROM attributes a
             LEFT JOIN attribute_items ai ON ai.attribute_id = a.id
             WHERE a.product_id = ?
             ORDER BY a.id, ai.id"
        );
        $stmt->execute([$productId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $grouped = [];
        foreach ($rows as $row) {
            $attrId = $row['id'];
            if (!isset($grouped[$attrId])) {
                $grouped[$attrId] = [
                    'id'    => $row['attribute_id'],
                    'name'  => $row['name'],
                    'type'  => $row['type'],
                    'items' => [],
                ];
            }
            if ($row['item_id'] !== null) {
                $grouped[$attrId]['items'][] = [
                    'id'           => $row['item_id'],
                    'displayValue' => $row['display_value'],
                    'value'        => $row['value'],
                ];
            }
        }

        return array_values(array_map(
            fn($data) => AttributeFactory::create($data)->toArray(),
            $grouped
        ));
    }
}
