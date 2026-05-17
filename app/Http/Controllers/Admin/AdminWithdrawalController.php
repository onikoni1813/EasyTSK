<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\UserNotification;
use App\Models\Withdrawal;
use App\Notifications\WithdrawalStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(25);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Request $request, Withdrawal $withdrawal)
    {
        $request->validate(['admin_feedback' => 'required|string|max:500']);

        // Lock row to prevent race condition
        $withdrawal = Withdrawal::where('id', $withdrawal->id)->lockForUpdate()->first();

        if ($withdrawal->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Already processed.']);
        }

        DB::transaction(function () use ($withdrawal, $request) {
            $user = $withdrawal->user;

            // Burn pending points permanently
            $user->decrement('pending_points', $withdrawal->amount_points);

            $withdrawal->update([
                'status' => 'approved',
                'admin_feedback' => $request->admin_feedback,
                'moderated_by' => Auth::id(),
                'moderated_at' => now()
            ]);

            Transaction::create([
                'user_id'     => $user->id,
                'amount_points' => -$withdrawal->amount_points,
                'amount_bdt'    => -$withdrawal->amount_bdt,
                'admin_profit'  => 0,
                'user_reward'   => 0,
                'type'          => 'withdrawal_approved',
                'source'        => $withdrawal->method,
                'description'   => 'উইথড্র অ্যাপ্রুভড — '.$withdrawal->method.' ('.$withdrawal->account.')',
                'status'        => 'completed',
            ]);

            // Built-in Notification
            $user->notify(new WithdrawalStatusUpdated($withdrawal));

            UserNotification::create([
                'user_id' => $user->id,
                'type'    => 'success',
                'title'   => '💸 পেমেন্ট পাঠানো হয়েছে!',
                'message' => '৳ '.number_format($withdrawal->amount_bdt, 2).' আপনার '.$withdrawal->method.' ('.$withdrawal->account.') তে পাঠানো হয়েছে। TrxID: '.$request->admin_feedback,
            ]);
        });

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Withdrawal approved!']);
        }

        return back()->with('success', 'Withdrawal approved!');
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        $request->validate(['admin_feedback' => 'required|string|max:500']);

        // Lock row to prevent race condition
        $withdrawal = Withdrawal::where('id', $withdrawal->id)->lockForUpdate()->first();

        if ($withdrawal->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Already processed.']);
        }

        DB::transaction(function () use ($withdrawal, $request) {
            $user = $withdrawal->user;

            // Refund: move pending_points back to points
            $user->decrement('pending_points', $withdrawal->amount_points);
            $user->increment('points', $withdrawal->amount_points);

            $withdrawal->update([
                'status' => 'rejected',
                'admin_feedback' => $request->admin_feedback,
                'admin_notes' => $request->admin_feedback,
                'moderated_by' => Auth::id(),
                'moderated_at' => now(),
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'amount_points' => $withdrawal->amount_points,
                'amount_bdt' => $withdrawal->amount_bdt,
                'admin_profit' => 0,
                'type' => 'withdrawal_refund',
                'description' => 'রিফান্ড: '.$request->admin_feedback,
                'status' => 'completed',
            ]);

            // Built-in Notification
            $user->notify(new WithdrawalStatusUpdated($withdrawal));

            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'warning',
                'title' => '⚠️ উইথড্র রিজেক্ট হয়েছে — পয়েন্ট ফেরত',
                'message' => 'কারণ: '.$request->admin_feedback.' — আপনার '.$withdrawal->amount_points.' পয়েন্ট মেইন ব্যালেন্সে ফেরত দেওয়া হয়েছে।',
            ]);
        });

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Rejected and refunded.']);
        }

        return back()->with('success', 'Rejected and refunded.');
    }

    /**
     * Confiscate: burn points, notify user with danger alert, optionally ban
     */
    public function confiscate(Request $request, Withdrawal $withdrawal)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
            'also_ban' => 'nullable|boolean',
        ]);

        // Lock row to prevent race condition
        $withdrawal = Withdrawal::where('id', $withdrawal->id)->lockForUpdate()->first();

        if ($withdrawal->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Already processed.']);
        }

        DB::transaction(function () use ($withdrawal, $request) {
            $user = $withdrawal->user;

            // Confiscate: burn pending_points permanently (no refund!)
            $user->decrement('pending_points', $withdrawal->amount_points);


            // Also reduce trust score
            $user->decrement('trust_score', 20);
            $user->refresh();
            if ($user->trust_score < 0) {
                $user->update(['trust_score' => 0]);
            }

            $withdrawal->update([
                'status' => 'confiscated',
                'admin_notes' => $request->admin_notes,
                'moderated_by' => Auth::id(),
                'moderated_at' => now(),
            ]);

            if ($request->also_ban) {
                $user->update(['is_banned' => true, 'ban_reason' => $request->admin_notes]);
            }

            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'danger',
                'title' => '🚫 ব্যালেন্স বাজেয়াপ্ত!',
                'message' => 'ফেক কাজের জন্য আপনার '.$withdrawal->amount_points.' পয়েন্ট বাজেয়াপ্ত করা হয়েছে। কারণ: '.$request->admin_notes,
            ]);
        });

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Confiscated!']);
        }

        return back()->with('success', 'Points confiscated!');
    }
}
