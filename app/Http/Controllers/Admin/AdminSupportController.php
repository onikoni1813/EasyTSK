<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SupportTicketReplied;

class AdminSupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with('user')->latest()->paginate(20);

        return view('admin.support.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        // Admin reads the ticket
        $ticket->update(['is_read' => true]);

        $ticket->load('messages.user');

        return view('admin.support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate(['message' => 'required|string']);

        TicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin_reply' => true,
            'is_read' => false, // Unread for user
        ]);

        $ticket->update(['status' => 'responded']);

        // Notify user
        $ticket->user->notify(new SupportTicketReplied($ticket, $request->message));

        return back()->with('success', 'Reply sent to user!');
    }

    public function close(SupportTicket $ticket)
    {
        $ticket->update(['status' => 'closed']);

        return back()->with('success', 'Ticket closed.');
    }
}
