<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct() {}

    // Display all tasks belonging to the authenticated user
    public function index(Request $request)
    {
        $tasks = Task::where('user_id', Auth::id())
            ->orderBy('due_date')
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    // Store a new task
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:150',
            'content'   => 'nullable|string',
            'due_date'  => 'nullable|date',
        ]);

        Task::create([
            'user_id'   => Auth::id(),
            'title'     => $validated['title'],
            'content'   => $validated['content'] ?? null,
            'due_date'  => $validated['due_date'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Task created successfully');
    }

    // Update an existing task
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

        $task->update([
            'title'        => $validated['title'],
            'content'      => $validated['content'] ?? null,
            'due_date'     => $validated['due_date'] ?? null,
            'completed_at' => isset($validated['completed'])
                ? ($validated['completed'] ? now() : null)
                : $task->completed_at,
        ]);

        return redirect()->back()->with('success', 'Task updated successfully');
    }

    // Soft delete a task
    public function destroy(Task $task)
    {
        // Prevent users from deleting tasks they do not own
        abort_if($task->user_id !== Auth::id(), 403);

        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully');
    }
}
