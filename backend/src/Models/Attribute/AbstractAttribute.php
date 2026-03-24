<?php

namespace App\Models\Attribute;

abstract class AbstractAttribute
{
    protected string $id;
    protected string $name;
    protected string $type;
    protected array $items = [];

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->type = $data['type'];
        
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $this->items[] = new AttributeItem($item);
            }
        }
    }

    abstract public function validate(string $value): bool;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'items' => array_map(fn($item) => $item->toArray(), $this->items),
        ];
    }
}
