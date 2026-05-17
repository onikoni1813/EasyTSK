<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;

class AdminSupportDataController extends Controller
{
    public function getUnreadCount()
    {
        $count = SupportTicket::where('is_read', false)->count();

        return response()->json([
            'unread_count' => $count,
        ]);
    }
}
