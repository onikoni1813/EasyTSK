<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskDomain;
use Illuminate\Http\Request;

class AdminTaskDomainController extends Controller
{
    public function index()
    {
        $domains = TaskDomain::latest()->paginate(20);
        return view('admin.domains.index', compact('domains'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required|url|unique:task_domains,domain',
            'ad_code_1' => 'nullable|string',
            'ad_code_2' => 'nullable|string',
            'ad_code_3' => 'nullable|string',
            'direct_link' => 'nullable|url',
            'is_active' => 'boolean'
        ]);

        TaskDomain::create([
            'domain' => rtrim($request->domain, '/'),
            'ad_code_1' => $request->ad_code_1,
            'ad_code_2' => $request->ad_code_2,
            'ad_code_3' => $request->ad_code_3,
            'direct_link' => $request->direct_link,
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Domain added successfully.');
    }

    public function update(Request $request, TaskDomain $domain)
    {
        $request->validate([
            'domain' => 'required|url|unique:task_domains,domain,' . $domain->id,
            'ad_code_1' => 'nullable|string',
            'ad_code_2' => 'nullable|string',
            'ad_code_3' => 'nullable|string',
            'direct_link' => 'nullable|url',
            'is_active' => 'boolean'
        ]);

        $domain->update([
            'domain' => rtrim($request->domain, '/'),
            'ad_code_1' => $request->ad_code_1,
            'ad_code_2' => $request->ad_code_2,
            'ad_code_3' => $request->ad_code_3,
            'direct_link' => $request->direct_link,
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Domain updated successfully.');
    }

    public function destroy(TaskDomain $domain)
    {
        $domain->delete();
        return back()->with('success', 'Domain removed successfully.');
    }

    public function toggle(TaskDomain $domain)
    {
        $domain->update(['is_active' => !$domain->is_active]);
        return back()->with('success', 'Domain status updated.');
    }
}
