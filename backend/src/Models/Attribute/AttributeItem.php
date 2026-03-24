<?php

namespace App\Models\Attribute;

class AttributeItem
{
    private string $id;
    private string $displayValue;
    private string $value;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->displayValue = $data['displayValue'] ?? $data['display_value'];
        $this->value = $data['value'];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'displayValue' => $this->displayValue,
            'value' => $this->value,
        ];
    }
}
