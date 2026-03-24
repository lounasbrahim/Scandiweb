<?php

namespace App\Models\Product;

class ClothesProduct extends AbstractProduct
{
    protected function getAdditionalFields(): array
    {
        return ['product_type' => 'clothing'];
    }
}
