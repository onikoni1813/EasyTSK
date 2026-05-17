<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;

class AdminReferralController extends Controller
{
    /**
     * Display a listing of referral logs.
     */
    public function index()
    {
        $referrals = Referral::with(['referrer', 'referredUser'])
            ->latest('id')
            ->paginate(50);

        return view('admin.referrals.index', compact('referrals'));
    }
}
