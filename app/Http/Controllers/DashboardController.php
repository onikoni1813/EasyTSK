<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard with all aggregated data.
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $rate = (int) Setting::get('point_conversion_rate', 100);
        $noticeBoard = Setting::get('notice_board_text');
        $noticeBoardEnabled = Setting::get('notice_board_enabled', false);
        $noticeBoardUrgent = Setting::get('notice_board_urgent', false);
        $unreadNotifications = $user->unreadUserNotifications()->limit(5)->get();
        $notifications = $user->userNotifications()->limit(15)->get();
        $referralsMade = $user->referralsMade()->count();
        $referralsBonusPending = $user->referralsMade()->where('status', 'Locked')->exists();

        // Check for unread admin replies in support tickets
        $unreadTicket = SupportTicket::where('user_id', $user->id)
            ->whereHas('messages', function ($query) {
                $query->where('is_admin_reply', true)->where('is_read', false);
            })
            ->with(['messages' => function ($query) {
                $query->where('is_admin_reply', true)->where('is_read', false)->latest();
            }])
            ->first();

        // Check for withdrawal status updates
        $withdrawalNotification = $user->unreadNotifications()
            ->where('type', 'App\Notifications\WithdrawalStatusUpdated')
            ->latest()
            ->first();

        // Conversion tracking for first earning
        if ($user->first_earning_at && !$user->first_earning_event_fired) {
            session()->flash('fire_event', 'FirstEarning');
            $user->update(['first_earning_event_fired' => true]);
        }

        return view('dashboard', compact(
            'user',
            'rate',
            'noticeBoard',
            'noticeBoardEnabled',
            'noticeBoardUrgent',
            'unreadNotifications',
            'notifications',
            'referralsMade',
            'referralsBonusPending',
            'unreadTicket',
            'withdrawalNotification'
        ));
    }
}
