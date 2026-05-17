<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalMethod;
use Illuminate\Http\Request;

class AdminWithdrawalMethodController extends Controller
{
    public function index()
    {
        $methods = WithdrawalMethod::orderBy('sort_order')->get();
        return view('admin.withdrawal_methods.index', compact('methods'));
    }

    public function create()
    {
        return view('admin.withdrawal_methods.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:50|unique:withdrawal_methods,name|regex:/^[a-z0-9_]+$/',
            'label'               => 'required|string|max:100',
            'icon_emoji'          => 'required|string|max:10',
            'min_amount'          => 'required|numeric|min:1',
            'charge_type'         => 'required|in:fixed,percent',
            'charge_value'        => 'required|numeric|min:0',
            'account_label'       => 'required|string|max:100',
            'account_placeholder' => 'required|string|max:50',
            'account_min_length'  => 'required|integer|min:1',
            'account_max_length'  => 'required|integer|min:1',
            'sort_order'          => 'required|integer|min:0',
            'instructions'        => 'nullable|string|max:500',
        ]);

        $data['is_active']   = $request->boolean('is_active');
        $data['color_class'] = $request->input('color_class', 'primary');

        WithdrawalMethod::create($data);

        return redirect()->route('admin.withdrawal-methods.index')
            ->with('success', '✅ নতুন Withdrawal Method যোগ করা হয়েছে!');
    }

    public function edit(WithdrawalMethod $withdrawalMethod)
    {
        return view('admin.withdrawal_methods.edit', compact('withdrawalMethod'));
    }

    public function update(Request $request, WithdrawalMethod $withdrawalMethod)
    {
        $data = $request->validate([
            'label'               => 'required|string|max:100',
            'icon_emoji'          => 'required|string|max:10',
            'min_amount'          => 'required|numeric|min:1',
            'charge_type'         => 'required|in:fixed,percent',
            'charge_value'        => 'required|numeric|min:0',
            'account_label'       => 'required|string|max:100',
            'account_placeholder' => 'required|string|max:50',
            'account_min_length'  => 'required|integer|min:1',
            'account_max_length'  => 'required|integer|min:1',
            'sort_order'          => 'required|integer|min:0',
            'instructions'        => 'nullable|string|max:500',
        ]);

        $data['is_active']   = $request->boolean('is_active');
        $data['color_class'] = $request->input('color_class', $withdrawalMethod->color_class);

        $withdrawalMethod->update($data);

        return redirect()->route('admin.withdrawal-methods.index')
            ->with('success', '✅ Method আপডেট হয়েছে!');
    }

    public function destroy(WithdrawalMethod $withdrawalMethod)
    {
        $withdrawalMethod->delete();
        return redirect()->route('admin.withdrawal-methods.index')
            ->with('success', '✅ Method মুছে ফেলা হয়েছে!');
    }

    /** Quick toggle active status via AJAX */
    public function toggle(WithdrawalMethod $withdrawalMethod)
    {
        $withdrawalMethod->update(['is_active' => ! $withdrawalMethod->is_active]);
        return response()->json([
            'success'   => true,
            'is_active' => $withdrawalMethod->is_active,
            'message'   => $withdrawalMethod->label . ' ' . ($withdrawalMethod->is_active ? 'চালু' : 'বন্ধ') . ' করা হয়েছে!',
        ]);
    }
}
