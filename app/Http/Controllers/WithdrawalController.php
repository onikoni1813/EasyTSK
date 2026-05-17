<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WithdrawalMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check Social Links — redirect to profile settings if missing
        if (empty($user->facebook_link) || empty($user->telegram_username)) {
            return redirect()->route('profile.edit')
                ->with('warning', '⚠️ উইথড্র করার আগে দয়া করে আপনার আসল Facebook ID লিংক এবং Telegram ইউজারনেম সেভ করুন। নিচের ফর্মটি পূরণ করে "Save" বাটনে ক্লিক করুন।');
        }

        $withdrawals = $user->withdrawals()->latest()->paginate(10);

        $activeMethods = WithdrawalMethod::active()->get();
        $rate          = (int) Setting::get('point_conversion_rate', 100);

        // Get user's notification for any confiscation alerts
        $alerts = $user->userNotifications()
            ->where('type', 'danger')
            ->where('is_read', false)
            ->get();

        // Task history for transparency
        $submissions = $user->submissions()->with('task')->latest()->limit(10)->get();

        // Check if user has a pending withdrawal already
        $hasPending = $user->withdrawals()->where('status', 'pending')->exists();

        return view('withdrawals.index', compact(
            'user',
            'withdrawals',
            'submissions',
            'activeMethods',
            'rate',
            'alerts',
            'hasPending'
        ));
    }

    public function store(Request $request)
    {
        $rate = (int) Setting::get('point_conversion_rate', 100);

        // Fetch the chosen method dynamically
        $methodModel = WithdrawalMethod::where('name', $request->input('method'))
            ->where('is_active', true)
            ->first();

        if (! $methodModel) {
            return back()->with('error', 'নির্বাচিত পেমেন্ট মেথড বর্তমানে উপলব্ধ নয়।');
        }

        $request->validate([
            'amount_bdt' => 'required|numeric|min:' . (float) $methodModel->min_amount,
            'method'     => 'required|string',
            'account'    => "required|string|min:{$methodModel->account_min_length}|max:{$methodModel->account_max_length}",
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->is_banned) {
            return back()->with('error', 'আপনার অ্যাকাউন্ট ব্যান করা হয়েছে।');
        }

        // KYC check — per-withdrawal threshold
        $isKycMandatory = (bool) Setting::get('require_kyc_for_withdrawal', false);
        $kycThreshold   = (float) Setting::get('kyc_threshold', 500);

        if ($user->kyc_status !== 'approved') {
            if ($isKycMandatory || (float) $request->amount_bdt >= $kycThreshold) {
                return back()->with('error', 'এই পরিমাণ উইথড্র করতে NID ভেরিফিকেশন (KYC) বাধ্যতামূলক। দয়া করে KYC সম্পন্ন করুন।');
            }
        }

        // Check Social Links — redirect to profile settings if missing
        if (empty($user->facebook_link) || empty($user->telegram_username)) {
            return redirect()->route('profile.edit')
                ->with('warning', '⚠️ উইথড্র করার আগে দয়া করে আপনার আসল Facebook ID লিংক এবং Telegram ইউজারনেম সেভ করুন। নিচের ফর্মটি পূরণ করে "Save" বাটনে ক্লিক করুন।');
        }

        $requiredPoints = (int) round($request->amount_bdt * $rate);

        // Check if user has a pending withdrawal already
        if ($user->withdrawals()->where('status', 'pending')->exists()) {
            return back()->with('error', 'আপনার একটি উইথড্র রিকোয়েস্ট ইতিমধ্যে প্রসেস হচ্ছে। সেটি সম্পন্ন হওয়ার পর আবার চেষ্টা করুন।');
        }

        // Check withdrawal cooldown
        $cooldownHours = (int) Setting::get('withdrawal_cooldown_hours', 24);
        if ($cooldownHours > 0) {
            $lastCompleted = $user->withdrawals()->where('status', 'approved')->latest('moderated_at')->first();
            if ($lastCompleted && $lastCompleted->moderated_at) {
                $nextAllowed = $lastCompleted->moderated_at->addHours($cooldownHours);
                if (now()->lt($nextAllowed)) {
                    $remaining = now()->diffForHumans($nextAllowed, ['parts' => 2, 'short' => true]);
                    return back()->with('error', "আপনার শেষ উইথড্র সম্পন্ন হয়েছে। অনুগ্রহ করে {$remaining} পর আবার চেষ্টা করুন।");
                }
            }
        }

        if ($user->points < $requiredPoints) {
            return back()->with('error', 'পর্যাপ্ত পয়েন্ট নেই। বর্তমান ব্যালেন্স: '.$user->points.' পয়েন্ট।');
        }

        // Dynamic fee calculation
        $feeAmount        = $methodModel->calculateFee((float) $request->amount_bdt);
        $receivableAmount = $request->amount_bdt - $feeAmount;

        DB::transaction(function () use ($user, $request, $methodModel, $requiredPoints, $receivableAmount, $feeAmount) {
            // ─── Double-Spend Protection: Lock points immediately ──────────
            $user->decrement('points', $requiredPoints);
            $user->increment('pending_points', $requiredPoints);

            Withdrawal::create([
                'user_id'           => $user->id,
                'amount_points'     => $requiredPoints,
                'amount_bdt'        => $request->amount_bdt,
                'receivable_amount' => $receivableAmount,
                'fee_amount'        => $feeAmount,
                'method'            => $methodModel->label,
                'account'           => $request->account,
                'status'            => 'pending',
            ]);

            Transaction::create([
                'user_id'           => $user->id,
                'amount_points'     => -$requiredPoints,
                'amount_bdt'        => -$request->amount_bdt,
                'receivable_amount' => $receivableAmount,
                'fee_amount'        => $feeAmount,
                'admin_profit'      => $feeAmount,
                'user_reward'       => 0,
                'type'              => 'withdrawal_request',
                'source'            => $methodModel->label,
                'description'       => 'উইথড্র রিকোয়েস্ট — '.$methodModel->label.' ('.$request->account.')',
                'status'            => 'pending',
            ]);
        });

        // ─── Direct Telegram Notification ──────────
        try {
            if (env('TELEGRAM_BOT_TOKEN') && env('TELEGRAM_CHAT_ID')) {
                $text = "💰 *New Withdrawal Request*\n"
                      . "👤 *User:* " . ($user->full_name ?? $user->name) . "\n"
                      . "💵 *Amount:* ৳" . $request->amount_bdt . "\n"
                      . "💳 *Method:* " . $methodModel->label . "\n"
                      . "📞 *Account:* " . $request->account;

                \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                    'chat_id'    => env('TELEGRAM_CHAT_ID'),
                    'text'       => $text,
                    'parse_mode' => 'Markdown',
                ]);
            }
        } catch (\Exception $e) {
            // Silently ignore if Telegram fails
        }

        session()->flash('fire_event', 'WithdrawRequest');

        return back()->with('success', '✅ আপনার উইথড্র রিকোয়েস্টটি সফল হয়েছে! আমাদের টিম ২৪-৪৮ ঘণ্টার মধ্যে আপনার '.$methodModel->label.' নম্বরে পেমেন্ট পাঠিয়ে দেবে।');
    }
}
