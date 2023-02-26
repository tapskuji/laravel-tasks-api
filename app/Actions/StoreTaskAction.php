<?php

namespace App\Actions;

use App\Models\Task;
use App\Services\CacheKeyService;
use Illuminate\Support\Facades\Cache;

class StoreTaskAction
{

    public function createTask(int $userId, array $validated)
    {
        $validated['user_id'] = $userId;
        $task = Task::create($validated);
        Cache::forget(CacheKeyService::generateKey($userId));
        return $task;
    }
}
