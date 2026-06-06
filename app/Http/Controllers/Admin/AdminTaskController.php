<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'title'                => 'required|string|max:255',
            'description'          => 'required|string',
            'points'               => 'required|integer|min:0',
            'admin_profit'         => 'required|numeric|min:0',
            'quota_max'            => 'required|integer|min:0',
            'type'                 => 'required|in:custom,timewall,adsterra,youtube,facebook,telegram',
            'external_link'        => 'sometimes|nullable|url',
            'cooldown_hours'       => 'nullable|integer|min:0',
            'secret_code'          => 'nullable|string|max:64',
            'secret_code_count'    => 'nullable|integer|min:1|max:10',
            'secret_codes.*'       => 'nullable|string|max:64',
            'image_proof_count'    => 'nullable|integer|min:1|max:10',
            'requires_text_proof'  => 'sometimes|boolean',
            'requires_image_proof' => 'sometimes|boolean',
            'requires_email_proof' => 'sometimes|boolean',
            'is_optional'          => 'sometimes|boolean',
            'instruction_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        // Fix empty string for external_link
        if (isset($validatedData['external_link']) && $validatedData['external_link'] === '') {
            $validatedData['external_link'] = null;
        }

        // Handle secret_codes array (additional codes beyond the first)
        $secretCodes = array_filter($request->input('secret_codes', []), fn($c) => $c !== '' && $c !== null);
        $validatedData['secret_codes']      = !empty($secretCodes) ? array_values($secretCodes) : null;
        $validatedData['secret_code_count'] = max(1, (int) $request->input('secret_code_count', 1));
        $validatedData['image_proof_count'] = max(1, (int) $request->input('image_proof_count', 1));

        // Handle multiple instruction images
        $imagePaths = [];
        if ($request->hasFile('instruction_images')) {
            foreach ($request->file('instruction_images') as $file) {
                $imagePaths[] = $file->store('task-instructions', 'public');
            }
        }
        $validatedData['instruction_images'] = !empty($imagePaths) ? $imagePaths : null;
        // 0 = Unlimited: quota_remaining -1 sentinel
        $qmax = (int) $validatedData['quota_max'];
        $validatedData['quota_remaining'] = $qmax === 0 ? -1 : $qmax;

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
            'title'                => 'required|string|max:255',
            'description'          => 'required|string',
            'points'               => 'required|integer|min:0',
            'admin_profit'         => 'required|numeric|min:0',
            'quota_max'            => 'required|integer|min:0',
            'type'                 => 'required|in:custom,timewall,adsterra,youtube,facebook,telegram',
            'external_link'        => 'sometimes|nullable|url',
            'cooldown_hours'       => 'nullable|integer|min:0',
            'secret_code'          => 'nullable|string|max:64',
            'secret_code_count'    => 'nullable|integer|min:1|max:10',
            'secret_codes.*'       => 'nullable|string|max:64',
            'image_proof_count'    => 'nullable|integer|min:1|max:10',
            'requires_text_proof'  => 'sometimes|boolean',
            'requires_image_proof' => 'sometimes|boolean',
            'requires_email_proof' => 'sometimes|boolean',
            'is_optional'          => 'sometimes|boolean',
            'instruction_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if (isset($validatedData['external_link']) && $validatedData['external_link'] === '') {
            $validatedData['external_link'] = null;
        }

        // Handle secret_codes
        $secretCodes = array_filter($request->input('secret_codes', []), fn($c) => $c !== '' && $c !== null);
        $validatedData['secret_codes']      = !empty($secretCodes) ? array_values($secretCodes) : null;
        $validatedData['secret_code_count'] = max(1, (int) $request->input('secret_code_count', 1));
        $validatedData['image_proof_count'] = max(1, (int) $request->input('image_proof_count', 1));

        // Handle instruction images
        $existingImages = $task->instruction_images ?? [];
        $removedIndexes = $request->input('remove_images', []);
        foreach ($removedIndexes as $idx) {
            if (isset($existingImages[$idx])) {
                Storage::disk('public')->delete($existingImages[$idx]);
                unset($existingImages[$idx]);
            }
        }
        $existingImages = array_values($existingImages);

        if ($request->hasFile('instruction_images')) {
            foreach ($request->file('instruction_images') as $file) {
                $existingImages[] = $file->store('task-instructions', 'public');
            }
        }
        $validatedData['instruction_images'] = !empty($existingImages) ? $existingImages : null;

        // Smart quota_remaining adjustment when quota_max changes
        if (isset($validatedData['quota_max'])) {
            $oldMax  = (int) $task->quota_max;
            $newMax  = (int) $validatedData['quota_max'];
            if ($newMax === 0) {
                // 0 = Unlimited
                $validatedData['quota_remaining'] = -1;
            } else {
                $used = $oldMax === 0
                    ? 0
                    : max(0, $oldMax - (int) $task->quota_remaining);
                $validatedData['quota_remaining'] = max(0, $newMax - $used);
            }
        }

        $task->update($validatedData);
        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        foreach ($task->instruction_images ?? [] as $img) {
            Storage::disk('public')->delete($img);
        }
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully!');
    }
}
