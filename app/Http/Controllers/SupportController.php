<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $tickets = $user->supportTickets()->latest()->paginate(10);

        return view('support.index', compact('tickets'));
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,normal,high',
        ]);

        DB::transaction(function () use ($request) {
            $ticket = SupportTicket::create([
                'user_id' => Auth::id(),
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'open',
                'priority' => $request->priority,
                'is_read' => false,
            ]);

            TicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'message' => $request->message,
                'is_admin_reply' => false,
            ]);
        });

        return redirect()->route('support.index')->with('success', 'Ticket created successfully!');
    }

    public function show(SupportTicket $ticket)
    {
        if ((int) $ticket->user_id !== (int) Auth::id()) {
            abort(403);
        }

        // Mark all admin replies for this ticket as read for the user
        TicketMessage::where('support_ticket_id', $ticket->id)
            ->where('is_admin_reply', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Also mark any database notifications as read
        Auth::user()->unreadNotifications()
            ->where('data->ticket_id', (string)$ticket->id)
            ->get()
            ->markAsRead();

        $ticket->load('messages.user');

        return view('support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        if ((int) $ticket->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $request->validate(['message' => 'required|string']);

        TicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin_reply' => false,
            'is_read' => false, // New message is unread for admin
        ]);

        $ticket->update([
            'status' => 'pending_admin',
            'is_read' => false, // Ticket has unread user content for admin
        ]);

        return back()->with('success', 'Reply sent!');
    }
}
