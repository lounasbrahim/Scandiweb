<?php

namespace App\Models;

class ClothesProduct extends AbstractProduct
{
    protected function getAdditionalFields(): array
    {
        return ['product_type' => 'clothing'];
    }
}
