<?php

namespace App\Models\Category;

class AllCategory extends AbstractCategory
{
    protected function buildQuery(): array
    {
        return [
            "SELECT * FROM products",
            []
        ];
    }
}
