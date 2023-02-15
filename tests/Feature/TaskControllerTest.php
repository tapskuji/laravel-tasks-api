<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        //todo
    }

    public function test_user_can_only_read_all_their_tasks()
    {
        $user = User::factory()->hasTasks(2)->create();
        $user2 = User::factory()->hasTasks(2)->create();
        $tasks = $user->tasks;

        $response = $this->actingAs($user)->getJson(route('tasks.list'));

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertSee($tasks[0]->title);
        $response->assertSee($tasks[1]->title);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'description', 'completed', 'dueDate', 'createdAt', 'updatedAt']
            ]
        ]);
    }

    public function test_user_can_read_a_single_task()
    {
        $user = User::factory()->hasTasks(3)->create();
        $tasks = $user->tasks;

        $response = $this->actingAs($user)->getJson(route('tasks.read', $tasks[1]->id));

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $tasks[1]->id,
                'title' => $tasks[1]->title,
                'description' => $tasks[1]->description,
                'completed' => $tasks[1]->completed,
                'dueDate' => $tasks[1]->due_date,
            ]
        ]);
    }

    public function test_user_cannot_read_a_task_that_belongs_to_another_user()
    {
        $user1 = User::factory()->hasTasks(3)->create();
        $user2 = User::factory()->hasTasks(3)->create();
        $tasks = $user2->tasks;

        $response = $this->actingAs($user1)->getJson(route('tasks.read', $tasks[0]->id));
        $response->assertForbidden();
    }

    public function test_user_can_create_a_task()
    {
        $user = User::factory()->create();
        $taskData = [
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(10),
            'completed' => fake()->randomElement([Task::INCOMPLETE_TASK, Task::COMPLETED_TASK]),
            'dueDate' => fake()->dateTimeBetween('+2 days', '+1 month')->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($user)->postJson(route('tasks.store'), $taskData);
        $response->assertCreated();
        $this->assertEquals($user->id, Task::find(1)->user_id);
        $response->assertJson([
            'data' => [
                'id' => 1,
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'completed' => $taskData['completed'],
                'dueDate' => $taskData['dueDate'],
            ]
        ]);
    }

    public function test_user_can_update_a_task()
    {
        $user = User::factory()->hasTasks(2)->create();
        $tasks = $user->tasks;
        $taskData = [
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(10),
            'completed' => fake()->randomElement([Task::INCOMPLETE_TASK, Task::COMPLETED_TASK]),
            'dueDate' => fake()->dateTimeBetween('+2 days', '+1 month')->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($user)->putJson(route('tasks.update', $tasks[1]->id), $taskData);
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $tasks[1]->id,
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'completed' => $taskData['completed'],
                'dueDate' => $taskData['dueDate'],
            ]
        ]);
    }

    public function test_user_cannot_update_a_task_that_does_not_belong_to_them()
    {
        $user1 = User::factory()->hasTasks(2)->create();
        $user2 = User::factory()->hasTasks(2)->create();
        $tasks = $user2->tasks;
        $taskData = [
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(10),
            'completed' => fake()->randomElement([Task::INCOMPLETE_TASK, Task::COMPLETED_TASK]),
            'dueDate' => fake()->dateTimeBetween('+2 days', '+1 month')->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($user1)->putJson(route('tasks.update', $tasks[1]->id), $taskData);
        $response->assertForbidden();
        $this->assertSame($tasks[1]->title, Task::find($tasks[1]->id)->title);
    }

    public function test_user_can_delete_a_task()
    {
        $user = User::factory()->hasTasks(2)->create();
        $tasks = $user->tasks;
        $response = $this->actingAs($user)->deleteJson(route('tasks.delete', $tasks[1]->id));
        $response->assertOk();
        $this->assertCount(1, Task::all());
    }

    public function test_user_cannot_delete_a_task_that_does_not_belong_to_them()
    {
        $user1 = User::factory()->hasTasks(2)->create();
        $user2 = User::factory()->hasTasks(2)->create();
        $tasks = $user2->tasks;

        $response = $this->actingAs($user1)->deleteJson(route('tasks.delete', $tasks[1]->id));

        $response->assertForbidden();
        $this->assertCount(4, Task::all());
    }

    public function test_tasks_should_be_ordered_by_updated_at()
    {
        $user = User::factory()->hasTasks(3)->create();
        $task = Task::find(2);
        $task->updated_at = fake()->dateTimeBetween('+2 days', '+1 month')->format('Y-m-d H:i:s');
        $task->save();

        $response = $this->actingAs($user)->getJson(route('tasks.list'));
        $response->assertJson([
            'data' => [
                [
                    'id' => 2,
                ],
                [
                    'id' => 1,
                ]
            ]
        ]);
    }

    public function test_user_can_filter_tasks()
    {
        $user = $this->createUserWithTasks();

        $url = '/api/tasks?search=physics&sort=completed&direction=asc';
        $response = $this->actingAs($user)->getJson($url);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertSee('Physics');
        $response->assertSee('physics');
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'description', 'completed', 'dueDate', 'createdAt', 'updatedAt']
            ]
        ]);

        $response->assertJson([
            'data' => [
                [
                    'id' => 1,
                ],
                [
                    'id' => 3,
                ]
            ]
        ]);
    }

    public function createUserWithTasks(): User
    {
        $user = User::factory()->create();

        $task0 = new Task();
        $task0->id = 1;
        $task0->user_id = $user->id;
        $task0->title = 'Study Physics';
        $task0->description = 'Study chapter 1';
        $task0->completed = 0;
        $task0->due_date = null;
        $task0->created_at = '2022-01-01 08:00:00';
        $task0->updated_at = '2022-01-01 08:00:00';
        $task0->save();


        $task1 = new Task();
        $task1->id = 2;
        $task1->user_id = $user->id;
        $task1->title = 'Math Assignment';
        $task1->description = 'Study chapter 2';
        $task1->completed = 0;
        $task1->due_date = null;
        $task1->created_at = '2022-02-01 08:00:00';
        $task1->updated_at = '2022-02-01 08:00:00';
        $task1->save();

        $task2 = new Task();
        $task2->id = 3;
        $task2->user_id = $user->id;
        $task2->title = 'Exam';
        $task2->description = 'Study for physics exam';
        $task2->completed = 1;
        $task2->due_date = '2022-03-01 08:00:00';
        $task2->created_at = '2022-03-01 08:00:00';
        $task2->updated_at = '2022-03-01 08:00:00';
        $task2->save();

        return $user;
    }
}
