<?php

namespace App\GraphQL\Resolvers;

use App\Models\Attribute\AttributeRepository;

class AttributeResolver
{
    private static ?AttributeRepository $repository = null;

    private static function getRepository(): AttributeRepository
    {
        if (self::$repository === null) {
            self::$repository = new AttributeRepository();
        }
        return self::$repository;
    }

    public static function forProduct(string $productId): array
    {
        return self::getRepository()->findByProductId($productId);
    }
}
