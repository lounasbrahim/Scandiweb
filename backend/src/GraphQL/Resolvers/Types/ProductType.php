<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Resolvers\AttributeResolver;

class ProductType extends ObjectType
{
    public function __construct(AttributeType $attributeType)
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => [
                'id' => Type::nonNull(Type::id()),
                'name' => Type::string(),
                'brand' => Type::string(),
                'description' => Type::string(),
                'in_stock' => Type::boolean(),
                'category' => Type::string(),
                'price' => Type::float(),
                'gallery' => Type::listOf(Type::string()),
                'product_type' => Type::string(),
                'attributes' => [
                    'type' => Type::listOf($attributeType),
                    'resolve' => fn($root) => AttributeResolver::forProduct($root['id']),
                ],
            ],
        ]);
    }
}
