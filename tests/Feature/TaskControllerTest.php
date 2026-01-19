<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskReminder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_display_user_tasks()
    {
        // Create tasks for this user and another user
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);
        Task::factory()->count(2)->create(['user_id' => User::factory()->create()->id]);

        $response = $this->actingAs($this->user)->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewHas('tasks');
        $this->assertCount(3, $response->viewData('tasks'));
    }

    /** @test */
    public function it_can_store_a_task_without_due_date()
    {
        $taskData = [
            'title' => 'Test Task',
            'content' => 'Test Content',
        ];

        $response = $this->actingAs($this->user)->post(route('tasks.store'), $taskData);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $this->user->id
        ]);
        $this->assertDatabaseCount('task_reminders', 0);
    }

    /** @test */
    public function it_can_store_a_task_with_due_date_and_creates_reminder()
    {
        $dueDate = now()->addDays(5)->format('Y-m-d H:i:s');
        $taskData = [
            'title' => 'Task with reminder',
            'due_date' => $dueDate,
        ];

        $response = $this->actingAs($this->user)->post(route('tasks.store'), $taskData);

        $response->assertRedirect();
        $task = Task::where('title', 'Task with reminder')->first();
        
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
        $this->assertDatabaseHas('task_reminders', [
            'task_id' => $task->id,
            'remind_at' => $dueDate
        ]);
    }

    /** @test */
    public function it_can_update_a_task_and_reminders()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'due_date' => now()->addDays(1)
        ]);
        
        // Create initial reminder
        TaskReminder::create([
            'task_id' => $task->id,
            'remind_at' => $task->due_date
        ]);

        $newDueDate = now()->addDays(10)->format('Y-m-d H:i:s');
        $updateData = [
            'title' => 'Updated Title',
            'due_date' => $newDueDate,
        ];

        $response = $this->actingAs($this->user)->put(route('tasks.update', $task), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title'
        ]);
        
        $this->assertDatabaseHas('task_reminders', [
            'task_id' => $task->id,
            'remind_at' => $newDueDate
        ]);
    }

    /** @test */
    public function it_deletes_pending_reminders_when_task_is_completed()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'due_date' => now()->addDays(1)
        ]);
        
        TaskReminder::create([
            'task_id' => $task->id,
            'remind_at' => $task->due_date
        ]);

        $updateData = [
            'title' => $task->title,
            'completed' => true,
        ];

        $response = $this->actingAs($this->user)->put(route('tasks.update', $task), $updateData);

        $this->assertDatabaseCount('task_reminders', 0);
        $this->assertNotNull($task->fresh()->completed_at);
    }

    /** @test */
    public function it_can_delete_a_task_and_its_reminders()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        TaskReminder::create([
            'task_id' => $task->id,
            'remind_at' => now()->addDay()
        ]);

        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $task));

        $response->assertRedirect();
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
        $this->assertDatabaseCount('task_reminders', 0);
    }

    /** @test */
    public function a_user_cannot_access_another_users_task()
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->put(route('tasks.update', $task), [
            'title' => 'Attempting hacking',
        ]);

        $response->assertStatus(403);
        
        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $task));
        $response->assertStatus(403);
    }
}
