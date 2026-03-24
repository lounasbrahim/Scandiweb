<?php

namespace App\GraphQL\Resolvers;

use App\Models\Category\CategoryRepository;

class CategoryResolver
{
    private static ?CategoryRepository $repository = null;

    private static function getRepository(): CategoryRepository
    {
        if (self::$repository === null) {
            self::$repository = new CategoryRepository();
        }
        return self::$repository;
    }

    public static function all(): array
    {
        return self::getRepository()->findAll();
    }
}
