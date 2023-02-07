<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_has_expected_table_tasks_and_columns()
    {
        $this->assertTrue(Schema::hasTable('tasks'), 'Tasks table is missing');

        $this->assertTrue(
            Schema::hasColumns('tasks', [
                'id', 'user_id', 'title', 'description', 'completed', 'due_date', 'created_at', 'updated_at',
            ]),
            'Tasks table is missing some columns'
        );
    }

    public function test_task_must_have_a_user_id()
    {
        $this->expectException(QueryException::class);
        $task = Task::factory()->create([
            'user_id' => null,
        ]);
    }
}
