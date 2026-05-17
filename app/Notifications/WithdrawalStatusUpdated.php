<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalStatusUpdated extends Notification
{
    use Queueable;

    protected $withdrawal;

    /**
     * Create a new notification instance.
     */
    public function __construct($withdrawal)
    {
        $this->withdrawal = $withdrawal;
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
            'withdrawal_id' => $this->withdrawal->id,
            'status' => $this->withdrawal->status,
            'amount_bdt' => $this->withdrawal->amount_bdt,
            'admin_feedback' => $this->withdrawal->admin_feedback,
            'message' => $this->getMessage(),
        ];
    }

    protected function getMessage()
    {
        if ($this->withdrawal->status === 'approved') {
            return "আপনার ৳ " . number_format($this->withdrawal->amount_bdt, 2) . " উইথড্র রিকোয়েস্ট অ্যাপ্রুভ করা হয়েছে। ট্রানজেকশন আইডি/মেসেজ: " . $this->withdrawal->admin_feedback;
        } elseif ($this->withdrawal->status === 'rejected') {
            return "আপনার ৳ " . number_format($this->withdrawal->amount_bdt, 2) . " উইথড্র রিকোয়েস্ট রিজেক্ট করা হয়েছে। কারণ: " . $this->withdrawal->admin_feedback;
        }

        return "আপনার উইথড্র রিকোয়েস্টের স্ট্যাটাস পরিবর্তন হয়েছে: " . strtoupper($this->withdrawal->status);
    }
}
