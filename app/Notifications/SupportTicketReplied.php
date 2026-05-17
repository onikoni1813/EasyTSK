<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SupportTicketReplied extends Notification
{
    use Queueable;

    protected $ticket;
    protected $reply;

    /**
     * Create a new notification instance.
     */
    public function __construct($ticket, $reply)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'subject' => $this->ticket->subject,
            'message' => 'সাপোর্ট টিকেট #' . str_pad($this->ticket->id, 5, '0', STR_PAD_LEFT) . ' এ একটি নতুন রিপ্লাই এসেছে।',
            'link' => route('support.show', $this->ticket->id),
        ];
    }
}
