<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $withdrawals = $user->withdrawals()->latest()->paginate(15, ['*'], 'withdrawals_page');
        $submissions = $user->submissions()->with('task')->latest()->paginate(15, ['*'], 'tasks_page');

        $rate = (int) Setting::get('point_conversion_rate', 100);

        return view('activity.index', compact('user', 'withdrawals', 'submissions', 'rate'));
    }
}
