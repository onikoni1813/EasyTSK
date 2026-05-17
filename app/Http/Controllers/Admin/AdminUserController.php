<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Submission;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    // ─── User List ────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = User::where('is_admin', false);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('bkash_number', 'like', "%{$q}%");
            });
        }

        $sortBy = $request->input('sort', 'latest');
        match ($sortBy) {
            'highest_profit' => $query->orderByDesc('total_admin_profit_generated'),
            'most_rejected' => $query->withCount(['submissions as rejected_count' => fn ($q) => $q->where('status', 'rejected')])->orderByDesc('rejected_count'),
            'highest_balance' => $query->orderByDesc('points'),
            'banned' => $query->where('is_banned', true)->latest(),
            default => $query->latest(),
        };

        $rate = (int) Setting::get('point_conversion_rate', 100);
        $users = $query->paginate(30)->withQueryString();

        return view('admin.users.index', compact('users', 'sortBy', 'rate'));
    }

    // ─── User 360° View ─────────────────────────────────────────────────────

    public function show(User $user)
    {
        $rate = (int) Setting::get('point_conversion_rate', 100);

        // ── Financial stats ────────────────────────────────────────────────
        $totalEarned = $user->transactions()->where('type', 'task_completion')->sum('amount_bdt');
        $totalWithdrawn = $user->withdrawals()->where('status', 'approved')->sum('amount_bdt');
        $currentBalance = $user->points / $rate;
        $netLTV = $user->total_admin_profit_generated;

        // ── Submission stats ───────────────────────────────────────────────
        $totalSubmissions = $user->submissions()->count();
        $approved = $user->submissions()->where('status', 'approved')->count();
        $rejected = $user->submissions()->where('status', 'rejected')->count();
        $pending = $user->submissions()->where('status', 'pending')->count();

        // ── Recent activity log ────────────────────────────────────────────
        $recentTransactions = $user->transactions()->latest()->limit(30)->get();

        // ── Referral info ──────────────────────────────────────────────────
        $referralsMade = $user->referralsMade()->with('referredUser')->latest()->get();
        $referralRecord = $user->referralRecord;

        return view('admin.users.show', compact(
            'user',
            'rate',
            'totalEarned',
            'totalWithdrawn',
            'currentBalance',
            'netLTV',
            'totalSubmissions',
            'approved',
            'rejected',
            'pending',
            'recentTransactions',
            'referralsMade',
            'referralRecord'
        ));
    }

    // ─── Manual Balance Adjustment ─────────────────────────────────────────

    public function adjustBalance(Request $request, User $user)
    {
        $request->validate([
            'points' => 'required|integer|not_in:0',
            'reason' => 'required|string|max:255',
        ]);

        $points = (int) $request->points;
        $rate = (int) Setting::get('point_conversion_rate', 100);

        DB::transaction(function () use ($user, $points, $request, $rate) {
            if ($points > 0) {
                $user->increment('points', $points);
            } else {
                $absPoints = abs($points);
                if ($user->points < $absPoints) {
                    throw new \Exception('Insufficient balance for deduction.');
                }
                $user->decrement('points', $absPoints);
            }

            Transaction::create([
                'user_id' => $user->id,
                'amount_points' => $points,
                'amount_bdt' => $points / $rate,
                'admin_profit' => 0,
                'type' => 'admin_adjustment',
                'source' => 'Admin',
                'description' => 'Admin manual adjustment: '.$request->reason,
                'status' => 'completed',
            ]);

            $type = $points > 0 ? 'info' : 'warning';
            UserNotification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $points > 0 ? '💰 অ্যাডমিন বোনাস!' : '⚠️ পয়েন্ট কাটা হয়েছে',
                'message' => abs($points).' পয়েন্ট '.($points > 0 ? 'যোগ' : 'কাটা').' হয়েছে। কারণ: '.$request->reason,
            ]);
        });

        return back()->with('success', 'Balance adjusted by '.$points.' points.');
    }

    // ─── Warn User ────────────────────────────────────────────────────────────

    public function warn(Request $request, User $user)
    {
        $request->validate(['message' => 'required|string|max:500']);

        UserNotification::create([
            'user_id' => $user->id,
            'type' => 'warning',
            'title' => '⚠️ অ্যাডমিন সতর্কতা',
            'message' => $request->message,
        ]);

        return back()->with('success', 'Warning sent to user.');
    }

    // ─── Ban / Suspend ────────────────────────────────────────────────────────

    public function ban(Request $request, User $user)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $user->update([
            'is_banned' => true,
            'ban_reason' => $request->reason,
        ]);

        UserNotification::create([
            'user_id' => $user->id,
            'type' => 'danger',
            'title' => '🚫 অ্যাকাউন্ট স্থগিত',
            'message' => 'নিয়ম লঙ্ঘনের কারণে আপনার অ্যাকাউন্ট বন্ধ করা হয়েছে। কারণ: '.$request->reason,
        ]);

        return back()->with('success', 'User banned.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false, 'ban_reason' => null]);

        return back()->with('success', 'User unbanned.');
    }

    // ─── Role Management (Sub-Admin / Moderator) ──────────────────────────────

    public function setRole(Request $request, User $user)
    {
        /** @var \App\Models\User $admin */
        $admin = \Illuminate\Support\Facades\Auth::user();

        if (! $admin->is_admin) {
            abort(403, 'Only the main admin can manage roles.');
        }

        $request->validate([
            'role' => 'required|in:user,moderator,sub_admin',
        ]);

        $user->update([
            'is_moderator'  => $request->role === 'moderator',
            'is_sub_admin'  => $request->role === 'sub_admin',
            'sub_admin_permissions' => $request->role === 'sub_admin'
                ? ($user->sub_admin_permissions ?? [])
                : null,
        ]);

        $roleLabels = ['user' => 'সাধারণ ইউজার', 'moderator' => 'মডারেটর', 'sub_admin' => 'সাব-অ্যাডমিন'];

        return back()->with('success', "✅ ইউজার {$user->name} কে {$roleLabels[$request->role]} করা হয়েছে।");
    }

    public function updatePermissions(Request $request, User $user)
    {
        /** @var \App\Models\User $admin */
        $admin = \Illuminate\Support\Facades\Auth::user();

        if (! $admin->is_admin) {
            abort(403);
        }

        if (! $user->is_sub_admin) {
            return back()->with('error', 'শুধুমাত্র সাব-অ্যাডমিনদের পারমিশন সেট করা যায়।');
        }

        $allowed = ['submissions', 'withdrawals', 'users', 'tasks', 'kyc', 'support', 'settings', 'fraud'];
        $perms   = array_intersect($request->input('permissions', []), $allowed);

        $user->update(['sub_admin_permissions' => array_values($perms)]);

        return back()->with('success', '✅ সাব-অ্যাডমিনের পারমিশন আপডেট হয়েছে।');
    }
}
