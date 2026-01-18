<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct() {}

    /**
     * Display all tasks belonging to the authenticated user
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $tasks = Auth::user()->tasks()->orderBy('due_date')->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Store a new task
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:150',
            'content'   => 'nullable|string',
            'due_date'  => 'nullable|date',
        ]);

        $task = Task::create([
            'user_id'   => Auth::id(),
            'title'     => $validated['title'],
            'content'   => $validated['content'] ?? null,
            'due_date'  => $validated['due_date'] ?? null,
        ]);

        if ($task->due_date) {
            // Create a reminder for the task
            TaskReminder::create([
                'task_id' => $task->id,
                'remind_at' => $task->due_date,
                'send_at' => null,
            ]);
        }
        return redirect()->back()->with('success', 'Task created successfully');
    }

    /**
     * Update an existing task
     * @param Request $request
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Task $task)
    {
        // Prevent users from modifying tasks they do not own
        abort_if($task->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title'     => 'required|string|max:150',
            'content'   => 'nullable|string',
            'due_date'  => 'nullable|date',
            'completed' => 'nullable|boolean',
        ]);

        $oldDueDate = $task->due_date;
        $newDueDate = $validated['due_date'] ?? null;
        $isCompleted = isset($validated['completed']) && $validated['completed'];

        $task->update([
            'title'        => $validated['title'],
            'content'      => $validated['content'] ?? null,
            'due_date'     => $newDueDate,
            'completed_at' => isset($validated['completed'])
                ? ($validated['completed'] ? now() : null)
                : $task->completed_at,
        ]);

        // Manage task reminders based on changes
        $this->manageReminders($task, $oldDueDate, $newDueDate, $isCompleted);

        return redirect()->back()->with('success', 'Task updated successfully');
    }

    /**
     * Manage task reminders when updating a task
     */
    protected function manageReminders(Task $task, $oldDueDate, $newDueDate, bool $isCompleted): void
    {
        // If task is completed, delete pending reminders
        if ($isCompleted) {
            TaskReminder::where('task_id', $task->id)
                ->whereNull('send_at')
                ->delete();
            return;
        }

        // Case 1: Added due date (didn't have before, now has)
        if (!$oldDueDate && $newDueDate) {
            TaskReminder::create([
                'task_id' => $task->id,
                'remind_at' => $newDueDate,
                'send_at' => null,
            ]);
            return;
        }

        // Case 2: Removed due date (had before, now doesn't)
        if ($oldDueDate && !$newDueDate) {
            TaskReminder::where('task_id', $task->id)
                ->whereNull('send_at')
                ->delete();
            return;
        }

        // Case 3: Changed due date
        if ($oldDueDate && $newDueDate && $oldDueDate != $newDueDate) {
            // Update existing reminder or create new one
            $reminder = TaskReminder::where('task_id', $task->id)
                ->whereNull('send_at')
                ->first();

            if (!$reminder) {
                TaskReminder::create([
                    'task_id' => $task->id,
                    'remind_at' => $newDueDate,
                    'send_at' => null,
                ]);
                return;
            }

            $reminder->update(['remind_at' => $newDueDate]);
            return;
        }
        return;
    }

    /**
     * Soft delete a task
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        // Prevent users from deleting tasks they do not own
        abort_if($task->user_id !== Auth::id(), 403);

        // Delete associated reminders (pending ones)
        TaskReminder::where('task_id', $task->id)
            ->whereNull('send_at')
            ->delete();

        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully');
    }
}
