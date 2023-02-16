<?php

namespace App\Actions;

use App\Models\Task;
use App\Services\CacheKeyService;
use Illuminate\Support\Facades\Cache;

class StoreTaskAction
{

    public function createTask(int $userId, array $validated)
    {
        $task = Task::create([
            'user_id' => $userId,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'completed' => $validated['completed'],
            'due_date' => $validated['dueDate'],
        ]);

        Cache::forget(CacheKeyService::generateKey($userId));
        return $task;
    }
}
