<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminKYCController extends Controller
{
    public function index()
    {
        $users = User::where('kyc_status', 'pending')->latest()->paginate(20);

        return view('admin.kyc.index', compact('users'));
    }

    public function viewImage(User $user, $side)
    {
        $path = $side === 'front' ? $user->id_front_path : $user->id_back_path;

        if (! $path || ! \Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return \Illuminate\Support\Facades\Storage::disk('local')->response($path);
    }

    public function approve(User $user)
    {
        if ($user->kyc_status !== 'pending') {
            return back()->with('error', 'KYC is not in pending status.');
        }

        // Auto-delete images to save space/security after approval
        $this->cleanupKYCFiles($user);

        $user->update([
            'kyc_status' => 'approved',
            'kyc_notes' => 'KYC approved by admin.',
            'id_front_path' => null,
            'id_back_path' => null,
        ]);

        return redirect()->route('admin.kyc.index')->with('success', 'KYC approved successfully! Files purged for security.');
    }

    public function reject(Request $request, User $user)
    {
        $request->validate(['kyc_notes' => 'required|string']);

        if ($user->kyc_status !== 'pending') {
            return back()->with('error', 'KYC is not in pending status.');
        }

        // Auto-delete images to save space/security after rejection
        $this->cleanupKYCFiles($user);

        $user->update([
            'kyc_status' => 'rejected',
            'kyc_notes' => $request->kyc_notes,
            'id_front_path' => null,
            'id_back_path' => null,
        ]);

        return redirect()->route('admin.kyc.index')->with('success', 'KYC rejected and files removed.');
    }

    private function cleanupKYCFiles(User $user)
    {
        if ($user->id_front_path) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($user->id_front_path);
        }
        if ($user->id_back_path) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($user->id_back_path);
        }
    }
}
