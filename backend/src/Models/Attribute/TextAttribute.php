<?php

namespace App\Models\Attribute;

class TextAttribute extends AbstractAttribute
{
    public function validate(string $value): bool
    {
        return in_array($value, array_column($this->items, 'value'), true);
    }
}
