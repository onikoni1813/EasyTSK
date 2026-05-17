<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAllRead(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        // Also handle the legacy UserNotification model if it exists
        if (method_exists($user, 'unreadUserNotifications')) {
            $user->unreadUserNotifications()->update(['is_read' => true]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    public function poll()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $unreadNotifications = $user->unreadNotifications()->latest()->limit(10)->get();
        $unreadCount = $user->unreadNotifications()->count();

        $notifications = $unreadNotifications->map(function($n) {
            return [
                'id' => $n->id,
                'message' => $n->data['message'] ?? 'New notification',
                'link' => $n->data['link'] ?? route('dashboard'),
                'created_at' => $n->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
            'urgent_notice' => (\App\Models\Setting::get('notice_board_enabled') && \App\Models\Setting::get('notice_board_urgent')) ? \App\Models\Setting::get('notice_board_text') : null
        ]);
    }

    public function dismissWithdrawal($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->unreadNotifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return back()->with('success', 'Notification dismissed.');
    }

    public function dismissTicket($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validate $id is a valid integer to prevent SQL injection
        if (!is_numeric($id) || (int) $id <= 0) {
            abort(404);
        }

        \App\Models\TicketMessage::whereHas('ticket', function($q) use ($user, $id) {
            $q->where('user_id', $user->id)->where('id', (int) $id);
        })->where('is_admin_reply', true)->update(['is_read' => true]);
        
        return back()->with('success', 'Ticket notification dismissed.');
    }
}
