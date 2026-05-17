<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class AdminTickerController extends Controller
{
    public function index()
    {
        $recentWithdrawals = Withdrawal::with('user')
            ->where('status', 'approved')
            ->latest()
            ->limit(20)
            ->get();

        $settings = [
            'fake_member_offset' => Setting::get('fake_member_offset', 1000),
            'fake_paid_offset' => Setting::get('fake_paid_offset', 5000),
            'fake_today_tasks_offset' => Setting::get('fake_today_tasks_offset', 200),
            'ticker_speed' => Setting::get('ticker_speed', '15s'),
            'ticker_custom_messages' => Setting::get('ticker_custom_messages', ''),
        ];

        return view('admin.ticker.index', compact('recentWithdrawals', 'settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'fake_member_offset' => 'required|integer|min:0',
            'fake_paid_offset' => 'required|numeric|min:0',
            'fake_today_tasks_offset' => 'required|integer|min:0',
            'ticker_speed' => 'required|string',
            'ticker_custom_messages' => 'nullable|string',
        ]);

        Setting::set('fake_member_offset', $request->fake_member_offset);
        Setting::set('fake_paid_offset', $request->fake_paid_offset);
        Setting::set('fake_today_tasks_offset', $request->fake_today_tasks_offset);
        Setting::set('ticker_speed', $request->ticker_speed);
        Setting::set('ticker_custom_messages', $request->ticker_custom_messages);

        return back()->with('success', 'Ticker parameters recalibrated successfully!');
    }
}
