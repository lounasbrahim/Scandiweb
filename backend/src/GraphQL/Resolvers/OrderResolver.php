<?php

namespace App\GraphQL\Resolvers;

use App\Models\Order\OrderRepository;

class OrderResolver
{
    private static ?OrderRepository $repository = null;

    private static function getRepository(): OrderRepository
    {
        if (self::$repository === null) {
            self::$repository = new OrderRepository();
        }
        return self::$repository;
    }

    public static function place($args): array
    {
        return self::getRepository()->save($args);
    }
}
