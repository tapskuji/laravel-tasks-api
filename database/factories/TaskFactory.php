<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(10),
            'completed' => fake()->randomElement([Task::INCOMPLETE_TASK, Task::COMPLETED_TASK]),
            'due_date' => fake()->randomElement([null, fake()->dateTimeThisYear()]),
        ];
    }
}
