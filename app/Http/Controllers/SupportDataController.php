<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;

class SupportDataController extends Controller
{
    public function getMessages(SupportTicket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark unread messages as read
        TicketMessage::where('support_ticket_id', $ticket->id)
            ->where('is_admin_reply', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $ticket->messages()->with('user')->get()->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'is_admin_reply' => (bool) $message->is_admin_reply,
                'sender_name' => $message->is_admin_reply ? 'Support Team' : ($message->user->full_name ?? $message->user->name),
                'sender_initial' => $message->is_admin_reply ? 'AD' : substr($message->user->full_name ?? $message->user->name, 0, 1),
                'created_at' => $message->created_at->format('d M, Y - h:i A'),
            ];
        });

        return response()->json([
            'status' => $ticket->status,
            'messages' => $messages,
        ]);
    }
}
