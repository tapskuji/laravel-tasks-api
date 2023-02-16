<?php

namespace App\Services;

class CacheKeyService
{

    public static function generateKey(int $userId): string
    {
        return "user_{$userId}_tasks";
    }
}
