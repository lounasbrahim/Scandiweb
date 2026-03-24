<?php

namespace App\Models\Category;

class TechCategory extends AbstractCategory
{
    protected function buildQuery(): array
    {
        return [
            "SELECT * FROM products WHERE category = ?",
            [$this->name]
        ];
    }
}
