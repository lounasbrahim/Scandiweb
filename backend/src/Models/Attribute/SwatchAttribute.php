<?php

namespace App\Models\Attribute;

class SwatchAttribute extends AbstractAttribute
{
    public function validate(string $value): bool
    {
        return (bool) preg_match('/^#[0-9A-Fa-f]{6}$/', $value)
            && in_array($value, array_column($this->items, 'value'), true);
    }
}
