<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;

class UserNotificationService
{
    public static function send(int $userId, string $type, string $title, string $message): void
    {
        UserNotification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ]);
    }

    public static function sendToAll(string $type, string $title, string $message): void
    {
        User::where('is_admin', false)->where('is_banned', false)->chunk(100, function ($users) use ($type, $title, $message) {
            foreach ($users as $user) {
                UserNotification::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                ]);
            }
        });
    }
}
