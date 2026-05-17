<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class AdminTaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->paginate(10);

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('admin.tasks.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points' => 'required|integer|min:0',
            'admin_profit' => 'required|numeric|min:0',
            'quota_max' => 'required|integer|min:1',
            'type' => 'required|in:custom,timewall,adsterra,youtube,facebook,telegram',
            'external_link' => 'nullable|url',
            'cooldown_hours' => 'nullable|integer|min:0',
            'secret_code' => 'nullable|string|max:64',
            'requires_text_proof' => 'boolean',
            'requires_image_proof' => 'boolean',
            'is_optional' => 'boolean',
        ]);

        $validatedData['quota_remaining'] = $validatedData['quota_max'];

        Task::create($validatedData);

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        return view('admin.tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points' => 'required|integer|min:0',
            'admin_profit' => 'required|numeric|min:0',
            'quota_max' => 'required|integer|min:1',
            'type' => 'required|in:custom,timewall,adsterra,youtube,facebook,telegram',
            'external_link' => 'nullable|url',
            'cooldown_hours' => 'nullable|integer|min:0',
            'secret_code' => 'nullable|string|max:64',
            'requires_text_proof' => 'boolean',
            'requires_image_proof' => 'boolean',
            'is_optional' => 'boolean',
        ]);

        // Smart quota_remaining adjustment when quota_max changes
        if (isset($validatedData['quota_max'])) {
            $oldMax = (int) $task->quota_max;
            $newMax = (int) $validatedData['quota_max'];
            $used   = $oldMax - (int) $task->quota_remaining; // how many already taken
            // remaining = new_max minus already used (never negative)
            $validatedData['quota_remaining'] = max(0, $newMax - $used);
        }

        $task->update($validatedData);

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully!');
    }
}
