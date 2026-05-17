<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KYCController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('kyc.index', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->kyc_status === 'approved' || $user->kyc_status === 'pending') {
            return back()->with('error', 'KYC already submitted or approved.');
        }

        $request->validate([
            'id_number' => 'required|string|max:50',
            'id_front' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'id_back' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $frontPath = $request->file('id_front')->store('kyc-proofs', 'local');
        $backPath = $request->file('id_back')->store('kyc-proofs', 'local');

        /** @var \App\Models\User $user */
        $user->update([
            'id_number' => $request->id_number,
            'id_front_path' => $frontPath,
            'id_back_path' => $backPath,
            'kyc_status' => 'pending',
        ]);

        return redirect()->route('kyc.index')->with('success', 'KYC submitted successfully! Please wait for admin review.');
    }
}
