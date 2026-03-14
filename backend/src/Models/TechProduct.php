<?php

namespace App\Models;

class TechProduct extends AbstractProduct
{
    protected function getAdditionalFields(): array
    {
        return ['product_type' => 'electronics'];
    }
}
