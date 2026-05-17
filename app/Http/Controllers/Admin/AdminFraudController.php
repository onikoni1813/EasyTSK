<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use App\Models\FraudLog;
use App\Models\User;

class AdminFraudController extends Controller
{
    /**
     * Display the Security Radar (Fraud Logs)
     */
    public function index()
    {
        $logs = FraudLog::latest()->paginate(50);

        // Collect all device fingerprints and IPs for a single batch query
        $deviceFingerprints = $logs->pluck('device_fingerprint')->filter()->unique()->values();
        $ipAddresses = $logs->pluck('ip_address')->filter()->unique()->values();

        // Single query to fetch all matching users
        $users = User::where(function ($q) use ($deviceFingerprints, $ipAddresses) {
            if ($deviceFingerprints->isNotEmpty()) {
                $q->whereIn('device_fingerprint', $deviceFingerprints->toArray());
            }
            if ($ipAddresses->isNotEmpty()) {
                $q->orWhereIn('registration_ip', $ipAddresses->toArray())
                  ->orWhereIn('last_ip', $ipAddresses->toArray());
            }
        })->get();

        // Map logs to their original user
        foreach ($logs as $log) {
            $log->originalUser = $users->first(function ($user) use ($log) {
                if ($log->device_fingerprint && $user->device_fingerprint === $log->device_fingerprint) {
                    return true;
                }
                if ($log->ip_address && ($user->registration_ip === $log->ip_address || $user->last_ip === $log->ip_address)) {
                    return true;
                }
                return false;
            });
        }

        return view('admin.fraud.index', compact('logs'));
    }

    /**
     * Ban Original User and Blacklist IP/Device permanently
     */
    public function banOriginalUser(FraudLog $log)
    {
        $user = null;

        if ($log->device_fingerprint) {
            $user = User::where('device_fingerprint', $log->device_fingerprint)->first();
            Blacklist::firstOrCreate(['type' => 'device', 'value' => $log->device_fingerprint]);
        }

        if ($log->ip_address) {
            if (! $user) {
                $user = User::where('registration_ip', $log->ip_address)->orWhere('last_ip', $log->ip_address)->first();
            }
            Blacklist::firstOrCreate(['type' => 'ip', 'value' => $log->ip_address]);
        }

        if ($user) {
            $user->update([
                'is_banned' => true,
                'ban_reason' => 'Fraud Radar: Detected multi-accounting or device forgery.',
            ]);
            $message = '🔒 User '.$user->name.' has been suspended and their network/device blacklisted.';
        } else {
            $message = '🛡️ Device & IP blacklisted completely. No connected existing user found.';
        }

        $log->delete(); // Clear the log once action is taken

        return back()->with('success', $message);
    }

    /**
     * Dismiss/Delete a log directly without action
     */
    public function destroy(FraudLog $log)
    {
        $log->delete();

        return back()->with('success', '🗑️ Log dismissed temporarily.');
    }
}
