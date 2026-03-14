<?php

namespace App\Models;

class AttributeFactory
{
    public static function create(array $data): AbstractAttribute
    {
        return match ($data['type']) {
            'swatch' => new SwatchAttribute($data),
            default => new TextAttribute($data),
        };
    }
}
